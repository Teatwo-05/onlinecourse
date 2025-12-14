<?php
require_once 'config/Database.php';
require_once 'models/Course.php';
require_once 'models/Lesson.php';

require_once 'BaseController.php';

class CourseController extends BaseController
{
    private $courseModel;
    private $categoryModel;
    private $enrollmentModel;
    private $lessonModel;
    private $materialModel;

    public function __construct()
    {
        
        require_once 'models/Course.php';
        require_once 'models/Category.php';
        require_once 'models/Enrollment.php';
        require_once 'models/Lesson.php';
        require_once 'models/Material.php';
        
        $this->courseModel = new Course();
        $this->categoryModel = new Category();
        $this->enrollmentModel = new Enrollment();
        $this->lessonModel = new Lesson();
        $this->materialModel = new Material();
    }

    public function index()
    {
        $keyword = $_GET['keyword'] ?? '';
        $category_id = $_GET['category'] ?? null;

        $categories = $this->categoryModel->getAll();


        $courses = [];
        if (!empty($keyword)) {
            $courses = $this->courseModel->searchCourses($keyword);
        } elseif (!empty($category_id)) {
            $courses = $this->courseModel->getCoursesByCategory($category_id);
        } else {
            $courses = $this->courseModel->getAllCourses();
        }

        $this->view('courses/index', [
            'courses' => $courses,
            'categories' => $categories,
            'keyword' => $keyword,
            'category_id' => $category_id
        ]);
    }

    public function detail()
    {
        $id = $_GET['id'] ?? 0;

        if (!$id) {
            $this->redirect('index.php?c=course&a=index');
            return;
        }

        $course = $this->courseModel->getCourseById($id);
        
        if (!$course) {
            $this->render404("Khóa học không tồn tại");
            return;
        }

        $lessons = $this->lessonModel->getLessonsByCourse($id);
        $materials = $this->materialModel->getMaterialsByCourse($id);
        

        $is_enrolled = false;
        if (!empty($_SESSION['user'])) {
            $is_enrolled = $this->enrollmentModel->isEnrolled($id, $_SESSION['user']['id']);
        }

        $this->view('courses/detail', [
            'course' => $course,
            'lessons' => $lessons,
            'materials' => $materials,
            'is_enrolled' => $is_enrolled
        ]);
    }



public function enroll()
{
if (empty($_SESSION['user'])) {
        $_SESSION['error'] = "Vui lòng đăng nhập để đăng ký khóa học.";
        header("Location: index.php?c=auth&a=login");
        exit;
    }

    $course_id = intval($_GET['id'] ?? 0);
    $student_id = $_SESSION['user']['id'];

    if (!$course_id) {
        $_SESSION['error'] = "Không thể tìm thấy ID khóa học để đăng ký.";
        $this->redirect('index.php?c=course&a=index');
        return;
    }


    $course = $this->courseModel->getCourseById($course_id);
    if (!$course) {
        $_SESSION['error'] = "Khóa học không tồn tại.";
        $this->redirect('index.php?c=course&a=index');
        return;
    }


    if ($this->enrollmentModel->isEnrolled($course_id, $student_id)) {
        $_SESSION['error'] = "Bạn đã đăng ký khóa học này rồi";
        $this->redirect("index.php?c=course&a=detail&id=$course_id");
        return;
    }


    $result = $this->enrollmentModel->enroll($course_id, $student_id);
    
    if ($result['success']) {
        $_SESSION['success'] = "Đăng ký khóa học thành công!";
    } else {
        $_SESSION['error'] = $result['message'];
    }
    
    $this->redirect("index.php?c=course&a=detail&id=$course_id");
}

    public function my_courses()
    {
        $this->require_role('instructor');

        $instructor_id = $_SESSION['user']['id'];
        $courses = $this->courseModel->getCoursesByInstructor($instructor_id);

        $this->view('instructor/my_courses', [
            'courses' => $courses
        ]);
    }


    public function create()
    {
        $this->require_role('instructor');

        $categories = $this->categoryModel->getAll();

        $this->view('instructor/course/create', [
            'categories' => $categories
        ]);
    }


    public function store()
    {
        $this->require_role('instructor');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=course&a=create');
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category_id = intval($_POST['category_id'] ?? 0);
        $price = floatval($_POST['price'] ?? 0);
        $duration_weeks = intval($_POST['duration_weeks'] ?? 4);
        $level = trim($_POST['level'] ?? 'beginner');
        $instructor_id = $_SESSION['user']['id'];


        if (empty($title) || empty($description) || $category_id <= 0) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin bắt buộc";
            $this->redirect('index.php?c=course&a=create');
            return;
        }


        $image = null;
        if (!empty($_FILES['image']['name'])) {
            $image = $this->uploadFile($_FILES['image'], "uploads/courses/");
        }


        $data = [
            'title' => $title,
            'description' => $description,
            'instructor_id' => $instructor_id,
            'category_id' => $category_id,
            'price' => $price,
            'duration_weeks' => $duration_weeks,
            'level' => $level,
            'image' => $image
        ];

        $result = $this->courseModel->createCourse($data);
        
        if ($result) {
            $_SESSION['success'] = "Tạo khóa học thành công!";
            $this->redirect('index.php?c=course&a=my_courses');
        } else {
            $_SESSION['error'] = "Tạo khóa học thất bại. Vui lòng thử lại!";
            $this->redirect('index.php?c=course&a=create');
        }
    }

    public function edit()
    {
        $this->require_role('instructor');

        $id = $_GET['id'] ?? 0;
        $course = $this->courseModel->getCourseById($id);

        if (!$course) {
            $this->render404("Khóa học không tồn tại");
            return;
        }


        if ($course['instructor_id'] != $_SESSION['user']['id']) {
            $this->render404("Bạn không có quyền chỉnh sửa khóa học này");
            return;
        }

        $categories = $this->categoryModel->getAll();

        $this->view('instructor/course/edit', [
            'course' => $course,
            'categories' => $categories
        ]);
    }


    public function update()
    {
        $this->require_role('instructor');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=course&a=my_courses');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        $course = $this->courseModel->getCourseById($id);

        if (!$course || $course['instructor_id'] != $_SESSION['user']['id']) {
            $this->render404("Khóa học không tồn tại hoặc bạn không có quyền");
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category_id = intval($_POST['category_id'] ?? 0);
        $price = floatval($_POST['price'] ?? 0);
        $duration_weeks = intval($_POST['duration_weeks'] ?? 4);
        $level = trim($_POST['level'] ?? 'beginner');


        if (empty($title) || empty($description) || $category_id <= 0) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin bắt buộc";
            $this->redirect("index.php?c=course&a=edit&id=$id");
            return;
        }


        $image = $course['image'];
        if (!empty($_FILES['image']['name'])) {
            $new_image = $this->uploadFile($_FILES['image'], "uploads/courses/");
            if ($new_image) {

                if ($image && file_exists($image)) {
                    unlink($image);
                }
                $image = $new_image;
            }
        }


        $data = [
            'title' => $title,
            'description' => $description,
            'category_id' => $category_id,
            'price' => $price,
            'duration_weeks' => $duration_weeks,
            'level' => $level,
            'image' => $image,
            'instructor_id' => $_SESSION['user']['id']
        ];

        $result = $this->courseModel->updateCourse($id, $data);
        
        if ($result) {
            $_SESSION['success'] = "Cập nhật khóa học thành công!";
        } else {
            $_SESSION['error'] = "Cập nhật thất bại";
        }
        
        $this->redirect('index.php?c=course&a=my_courses');
    }

    public function delete()
    {
        $this->require_role('instructor');

        $id = $_GET['id'] ?? 0;
        
        if ($id) {
            $course = $this->courseModel->getCourseById($id);
            

            if ($course && $course['instructor_id'] == $_SESSION['user']['id']) {
                $result = $this->courseModel->deleteCourse($id, $_SESSION['user']['id']);
                
                if ($result) {

                    if ($course['image'] && file_exists($course['image'])) {
                        unlink($course['image']);
                    }
                    $_SESSION['success'] = "Xóa khóa học thành công!";
                } else {
                    $_SESSION['error'] = "Xóa khóa học thất bại!";
                }
            }
        }

        $this->redirect("index.php?c=course&a=my_courses");
    }


}
