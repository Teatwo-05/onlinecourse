<?php

class CourseController extends BaseController
{
    private $courseModel;
    private $categoryModel;
    private $enrollmentModel;
    private $lessonModel;
    private $materialModel;

    public function __construct()
    {
        $this->courseModel     = new Course();
        $this->categoryModel   = new Category();
        $this->enrollmentModel = new Enrollment();
        $this->lessonModel     = new Lesson();
        $this->materialModel   = new Material();
    }

    /**
     * ================================
     *  STUDENT: LIST COURSES
     * ================================
     */
    public function index()
    {
        $keyword  = $_GET['keyword'] ?? null;
        $category = $_GET['category'] ?? null;

        $categories = $this->categoryModel->getAll();

        $courses = $this->courseModel->searchCourses($keyword, $category);

        $this->view('courses/index', [
            'courses'    => $courses,
            'categories' => $categories
        ]);
    }

    /**
     * ================================
     *  STUDENT: COURSE DETAIL
     * ================================
     */
    public function detail()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('index.php?controller=Course&action=index');
        }

        $course = $this->courseModel->findById($id);
        $lessons = $this->lessonModel->getLessonsByCourse($id);
        $materials = $this->materialModel->getMaterialsByCourse($id);

        if (!$course) {
            $this->render404("Khóa học không tồn tại");
            return;
        }

        $this->view('courses/detail', [
            'course'    => $course,
            'lessons'   => $lessons,
            'materials' => $materials,
        ]);
    }

    /**
     * ================================
     *  STUDENT: ENROLL COURSE
     * ================================
     */
    public function enroll()
    {
        $this->require_login();

        $courseId = $_GET['id'] ?? null;
        $userId   = $_SESSION['user']['id'];

        if (!$courseId) {
            $this->redirect('index.php?controller=Course&action=index');
        }

        // Check if already enrolled
        if ($this->enrollmentModel->isEnrolled($userId, $courseId)) {
            $this->redirect("index.php?controller=Course&action=detail&id=$courseId");
        }

        $this->enrollmentModel->createEnrollment($userId, $courseId);

        $this->redirect("index.php?controller=Course&action=detail&id=$courseId");
    }

    /**
     * ================================
     *  INSTRUCTOR: LIST MY COURSES
     * ================================
     */
    public function my_courses()
    {
        $this->require_role('instructor');

        $instructorId = $_SESSION['user']['id'];

        $courses = $this->courseModel->getCoursesByInstructor($instructorId);

        $this->view('instructor/my_courses', [
            'courses' => $courses
        ]);
    }

    /**
     * ================================
     *  INSTRUCTOR: CREATE COURSE (FORM)
     * ================================
     */
    public function create()
    {
        $this->require_role('instructor');

        $categories = $this->categoryModel->getAll();

        $this->view('instructor/course/create', [
            'categories' => $categories
        ]);
    }

    /**
     * ================================
     *  INSTRUCTOR: STORE COURSE
     * ================================
     */
    public function store()
    {
        $this->require_role('instructor');

        $title       = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $categoryId  = $_POST['category_id'] ?? '';
        $instructorId = $_SESSION['user']['id'];

        // Upload ảnh khóa học (nếu có)
        $thumbnail = null;
        if (!empty($_FILES['thumbnail']['name'])) {
            $thumbnail = $this->uploadFile($_FILES['thumbnail'], "uploads/courses/");
        }

        $this->courseModel->create([
            'title'        => $title,
            'description'  => $description,
            'category_id'  => $categoryId,
            'instructor_id'=> $instructorId,
            'thumbnail'    => $thumbnail
        ]);

        $this->redirect("index.php?controller=Course&action=my_courses");
    }

    /**
     * ================================
     *  INSTRUCTOR: EDIT COURSE
     * ================================
     */
    public function edit()
    {
        $this->require_role('instructor');

        $id = $_GET['id'] ?? null;
        $course = $this->courseModel->findById($id);

        if (!$course) {
            $this->render404("Khóa học không tồn tại");
            return;
        }

        $categories = $this->categoryModel->getAll();

        $this->view('instructor/course/edit', [
            'course'     => $course,
            'categories' => $categories
        ]);
    }

    /**
     * ================================
     *  INSTRUCTOR: UPDATE COURSE
     * ================================
     */
    public function update()
    {
        $this->require_role('instructor');

        $id = $_POST['id'] ?? null;
        $course = $this->courseModel->findById($id);

        if (!$course) {
            $this->render404("Khóa học không tồn tại");
            return;
        }

        $thumbnail = $course['thumbnail'];

        if (!empty($_FILES['thumbnail']['name'])) {
            $thumbnail = $this->uploadFile($_FILES['thumbnail'], "uploads/courses/");
        }

        $this->courseModel->update($id, [
            'title'       => $_POST['title'],
            'description' => $_POST['description'],
            'category_id' => $_POST['category_id'],
            'thumbnail'   => $thumbnail
        ]);

        $this->redirect("index.php?controller=Course&action=my_courses");
    }

    /**
     * ================================
     *  INSTRUCTOR: DELETE COURSE
     * ================================
     */
    public function delete()
    {
        $this->require_role('instructor');

        $id = $_GET['id'] ?? null;

        if ($id) {
            $this->courseModel->delete($id);
        }

        $this->redirect("index.php?controller=Course&action=my_courses");
    }
}
