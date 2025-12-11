/**
 * assets/js/script.js
 * Script chung cho frontend của project MVC (student / instructor / admin).
 *
 * Hướng tiếp cận:
 * - App.api: wrapper fetch cho các endpoint (tuân theo MVC routes backend).
 * - App.ui: helper thao tác DOM, render, thông báo.
 * - Modules: App.student, App.instructor, App.admin chứa logic chức năng.
 *
 * Lưu ý:
 * - Backend cần cung cấp các endpoint tương ứng (ví dụ: /api/courses, /api/courses/:id, /api/enroll, /auth/login, /instructor/courses, /admin/users...).
 * - CSRF token lấy từ <meta name="csrf-token" content="..."> nếu backend yêu cầu.
 * - Không dùng thư viện ngoài để dễ tích hợp vào project PHP thuần MVC.
 */

/* global fetch */
const App = (() => {
  // --- Utils ---
  const utils = {
    qs: (sel, root = document) => root.querySelector(sel),
    qsa: (sel, root = document) => Array.from(root.querySelectorAll(sel)),
    el: (tag, attrs = {}, children = []) => {
      const e = document.createElement(tag);
      Object.entries(attrs).forEach(([k, v]) => {
        if (k === 'class') e.className = v;
        else if (k === 'text') e.textContent = v;
        else if (k === 'html') e.innerHTML = v;
        else e.setAttribute(k, v);
      });
      children.forEach(c => e.appendChild(c));
      return e;
    },
    show: (sel) => {
      const el = utils.qs(sel);
      if (el) el.style.display = '';
    },
    hide: (sel) => {
      const el = utils.qs(sel);
      if (el) el.style.display = 'none';
    },
    formatDate: (iso) => {
      if (!iso) return '';
      const d = new Date(iso);
      return d.toLocaleString();
    },
    getCSRF: () => {
      const m = document.querySelector('meta[name="csrf-token"]');
      return m ? m.getAttribute('content') : '';
    },
    handleFetchErrors: async (resp) => {
      if (!resp.ok) {
        let message = resp.statusText;
        try {
          const data = await resp.json();
          if (data && data.error) message = data.error;
          else if (data && data.message) message = data.message;
        } catch (e) { /* ignore */ }
        const err = new Error(message || `HTTP ${resp.status}`);
        err.status = resp.status;
        throw err;
      }
      return resp;
    }
  };

  // --- API wrapper ---
  const api = {
    _base: '/api', // default base; adjust to match backend routing
    headers(json = true) {
      const h = {
        'Accept': 'application/json'
      };
      if (json) h['Content-Type'] = 'application/json';
      const csrf = utils.getCSRF();
      if (csrf) h['X-CSRF-Token'] = csrf;
      return h;
    },
    async get(path, opts = {}) {
      const res = await fetch(this._base + path, {
        method: 'GET',
        credentials: 'include',
        headers: this.headers(false),
        ...opts
      });
      await utils.handleFetchErrors(res);
      return res.json();
    },
    async post(path, data, opts = {}) {
      const body = data instanceof FormData ? data : JSON.stringify(data);
      const headers = data instanceof FormData ? { 'Accept': 'application/json', 'X-CSRF-Token': utils.getCSRF() } : this.headers();
      const res = await fetch(this._base + path, {
        method: 'POST',
        credentials: 'include',
        body,
        headers,
        ...opts
      });
      await utils.handleFetchErrors(res);
      return res.json();
    },
    async put(path, data, opts = {}) {
      const res = await fetch(this._base + path, {
        method: 'PUT',
        credentials: 'include',
        headers: this.headers(),
        body: JSON.stringify(data),
        ...opts
      });
      await utils.handleFetchErrors(res);
      return res.json();
    },
    async del(path, opts = {}) {
      const res = await fetch(this._base + path, {
        method: 'DELETE',
        credentials: 'include',
        headers: this.headers(false),
        ...opts
      });
      await utils.handleFetchErrors(res);
      return res.json();
    }
  };

  // --- UI helpers ---
  const ui = {
    container: null,
    init(containerSelector = '#app') {
      this.container = utils.qs(containerSelector) || document.body;
    },
    notify(msg, type = 'info') {
      // simple toast using alert fallback
      const toast = utils.el('div', { class: `toast toast-${type}`, html: `<strong>${type}:</strong> ${msg}` });
      toast.style.position = 'fixed';
      toast.style.right = '20px';
      toast.style.bottom = '20px';
      toast.style.padding = '10px 14px';
      toast.style.background = type === 'error' ? '#c33' : (type === 'success' ? '#2a9d8f' : '#333');
      toast.style.color = '#fff';
      toast.style.borderRadius = '6px';
      toast.style.zIndex = 9999;
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 4000);
    },
    renderCourseList(targetSelector, courses = []) {
      const target = utils.qs(targetSelector);
      if (!target) return;
      target.innerHTML = '';
      if (!courses.length) {
        target.innerHTML = '<p>Không có khóa học nào.</p>';
        return;
      }
      const ul = utils.el('div', { class: 'course-list' });
      courses.forEach(c => {
        const card = utils.el('div', { class: 'course-card' });
        card.innerHTML = `
          <h3 class="course-title">${c.title}</h3>
          <p class="course-meta">Category: ${c.category_name || 'N/A'} · Giá: ${c.price ? c.price + 'đ' : 'Miễn phí'}</p>
          <p class="course-desc">${c.short_desc || ''}</p>
          <div class="course-actions">
            <button class="btn-view" data-id="${c.id}">Xem chi tiết</button>
            <button class="btn-enroll" data-id="${c.id}">Đăng ký</button>
          </div>
        `;
        ul.appendChild(card);
      });
      target.appendChild(ul);
    },
    renderCourseDetail(targetSelector, course = {}) {
      const target = utils.qs(targetSelector);
      if (!target) return;
      target.innerHTML = `
        <article class="course-detail">
          <h2>${course.title || ''}</h2>
          <p><strong>Danh mục:</strong> ${course.category_name || ''}</p>
          <p><strong>Giá:</strong> ${course.price ? course.price + 'đ' : 'Miễn phí'}</p>
          <p>${course.description || ''}</p>
          <div>
            <button id="enroll-btn" data-id="${course.id}">Đăng ký khóa học</button>
          </div>
          <hr/>
          <section id="lessons-list"></section>
        </article>
      `;
    },
    renderLessons(targetSelector, lessons = []) {
      const target = utils.qs(targetSelector);
      if (!target) return;
      target.innerHTML = '';
      if (!lessons.length) { target.innerHTML = '<p>Chưa có bài học.</p>'; return; }
      const list = utils.el('ul');
      lessons.forEach(l => {
        const li = utils.el('li');
        li.innerHTML = `<strong>${l.title}</strong> <small>(${utils.formatDate(l.published_at)})</small>
                        <div><button class="btn-view-lesson" data-id="${l.id}">Xem bài</button></div>`;
        list.appendChild(li);
      });
      target.appendChild(list);
    },
    renderStudentsTable(targetSelector, students = []) {
      const target = utils.qs(targetSelector);
      if (!target) return;
      target.innerHTML = '';
      if (!students.length) { target.innerHTML = '<p>Chưa có học viên đăng ký.</p>'; return; }
      const table = utils.el('table', { class: 'table-students' });
      table.innerHTML = `<thead><tr><th>Họ tên</th><th>Email</th><th>Trạng thái</th><th>Tiến độ</th></tr></thead>`;
      const tbody = utils.el('tbody');
      students.forEach(s => {
        const tr = utils.el('tr');
        tr.innerHTML = `<td>${s.name}</td><td>${s.email}</td><td>${s.status}</td><td>${s.progress || '0%'}</td>`;
        tbody.appendChild(tr);
      });
      table.appendChild(tbody);
      target.appendChild(table);
    },
    renderUsersTable(targetSelector, users = []) {
      const target = utils.qs(targetSelector);
      if (!target) return;
      target.innerHTML = '';
      if (!users.length) { target.innerHTML = '<p>Không có người dùng.</p>'; return; }
      const table = utils.el('table', { class: 'table-users' });
      table.innerHTML = `<thead><tr><th>ID</th><th>Họ tên</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>`;
      const tbody = utils.el('tbody');
      users.forEach(u => {
        const tr = utils.el('tr');
        tr.innerHTML = `<td>${u.id}</td><td>${u.name}</td><td>${u.email}</td><td>${u.role}</td><td>${u.active ? 'Active' : 'Disabled'}</td>
                        <td>
                          <button class="btn-toggle-user" data-id="${u.id}" data-active="${u.active}">${u.active ? 'Vô hiệu' : 'Kích hoạt'}</button>
                        </td>`;
        tbody.appendChild(tr);
      });
      table.appendChild(tbody);
      target.appendChild(table);
    }
  };

  // --- Student Module ---
  const student = {
    init(selectors = {}) {
      // bind search form
      const searchForm = utils.qs(selectors.searchForm || '#course-search-form');
      if (searchForm) {
        searchForm.addEventListener('submit', async (e) => {
          e.preventDefault();
          const q = utils.qs('input[name="q"]', searchForm).value.trim();
          const cat = utils.qs('select[name="category"]', searchForm).value;
          await this.searchCourses({ q, category: cat });
        });
      }

      // delegate for course list actions
      document.addEventListener('click', async (e) => {
        if (e.target.matches('.btn-view')) {
          const id = e.target.dataset.id;
          await this.viewCourseDetail(id);
        } else if (e.target.matches('.btn-enroll')) {
          const id = e.target.dataset.id;
          await this.enrollCourse(id);
        } else if (e.target.matches('#enroll-btn')) {
          const id = e.target.dataset.id;
          await this.enrollCourse(id);
        } else if (e.target.matches('.btn-view-lesson')) {
          const id = e.target.dataset.id;
          await this.viewLesson(id);
        }
      });

      // load initial list
      this.searchCourses({});
    },
    async searchCourses({ q = '', category = '', page = 1 } = {}) {
      try {
        const query = new URLSearchParams({ q, category, page }).toString();
        const data = await api.get(`/courses?${query}`);
        // expect data.courses array
        ui.renderCourseList('#courses-container', data.courses || []);
      } catch (err) {
        ui.notify(err.message || 'Lỗi khi tải danh sách khóa học', 'error');
      }
    },
    async viewCourseDetail(id) {
      try {
        const data = await api.get(`/courses/${id}`);
        ui.renderCourseDetail('#course-detail', data.course || {});
        ui.renderLessons('#lessons-list', data.lessons || []);
      } catch (err) {
        ui.notify(err.message || 'Lỗi khi tải chi tiết khóa học', 'error');
      }
    },
    async enrollCourse(courseId) {
      try {
        if (!confirm('Xác nhận đăng ký khóa học này?')) return;
        const res = await api.post(`/courses/${courseId}/enroll`, {});
        ui.notify(res.message || 'Đăng ký thành công', 'success');
        // Optionally refresh enrolled list or course detail
      } catch (err) {
        ui.notify(err.message || 'Đăng ký thất bại', 'error');
      }
    },
    async viewEnrolled() {
      try {
        const data = await api.get(`/users/me/enrollments`);
        // render list
        ui.renderCourseList('#enrolled-container', data.courses || []);
      } catch (err) {
        ui.notify(err.message || 'Lỗi khi tải khóa học đã đăng ký', 'error');
      }
    },
    async viewLesson(lessonId) {
      try {
        const data = await api.get(`/lessons/${lessonId}`);
        // show modal or detail area
        const container = utils.qs('#lesson-detail') || document.body;
        const html = `
          <div class="lesson-view">
            <h3>${data.lesson.title}</h3>
            <p>${data.lesson.content || ''}</p>
            ${data.materials && data.materials.length ? '<h4>Tài liệu</h4>' : ''}
            <ul>${(data.materials || []).map(m => `<li><a href="${m.url}" target="_blank">${m.name}</a></li>`).join('')}</ul>
          </div>
        `;
        if (utils.qs('#lesson-detail')) container.innerHTML = html;
        else {
          const win = window.open('', '_blank');
          win.document.write(html);
          win.document.close();
        }
      } catch (err) {
        ui.notify(err.message || 'Lỗi khi tải bài học', 'error');
      }
    },
    async trackProgress(courseId) {
      try {
        const data = await api.get(`/courses/${courseId}/progress`);
        // render progress UI
        const container = utils.qs('#progress-container');
        if (!container) return;
        container.innerHTML = `<p>Tiến độ: ${data.progress || '0%'}</p>`;
      } catch (err) {
        ui.notify(err.message || 'Lỗi khi tải tiến độ', 'error');
      }
    }
  };

  // --- Instructor Module ---
  const instructor = {
    init(selectors = {}) {
      // bind auth forms
      const loginForm = utils.qs(selectors.loginForm || '#instructor-login-form');
      if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
          e.preventDefault();
          const form = loginForm;
          const email = utils.qs('input[name="email"]', form).value;
          const password = utils.qs('input[name="password"]', form).value;
          await this.login({ email, password });
        });
      }

      // create/edit/delete course
      document.addEventListener('click', async (e) => {
        if (e.target.matches('#create-course-btn')) {
          await this.openCourseEditor();
        } else if (e.target.matches('.btn-edit-course')) {
          const id = e.target.dataset.id;
          await this.openCourseEditor(id);
        } else if (e.target.matches('.btn-delete-course')) {
          const id = e.target.dataset.id;
          await this.deleteCourse(id);
        } else if (e.target.matches('#save-course-btn')) {
          await this.saveCourse();
        } else if (e.target.matches('.btn-upload-material')) {
          const lessonId = e.target.dataset.lessonId;
          await this.openUploadDialog(lessonId);
        } else if (e.target.matches('#logout-btn')) {
          await this.logout();
        }
      });

      // load instructor's courses
      this.loadMyCourses();
    },
    async login({ email, password }) {
      try {
        const res = await api.post('/auth/login', { email, password });
        ui.notify('Đăng nhập thành công', 'success');
        // maybe redirect or reload
        window.location.reload();
      } catch (err) {
        ui.notify(err.message || 'Đăng nhập thất bại', 'error');
      }
    },
    async logout() {
      try {
        await api.post('/auth/logout', {});
        ui.notify('Đã đăng xuất', 'success');
        window.location.reload();
      } catch (err) {
        ui.notify('Lỗi khi đăng xuất', 'error');
      }
    },
    async loadMyCourses() {
      try {
        const data = await api.get('/instructor/courses');
        ui.renderCourseList('#instructor-courses', data.courses || []);
      } catch (err) {
        ui.notify(err.message || 'Lỗi khi tải khóa học của giảng viên', 'error');
      }
    },
    async openCourseEditor(courseId = null) {
      try {
        let course = {};
        if (courseId) {
          const res = await api.get(`/instructor/courses/${courseId}`);
          course = res.course || {};
        }
        // render editor form in modal or area
        const editor = utils.qs('#course-editor') || utils.el('div', { id: 'course-editor' });
        editor.innerHTML = `
          <h3>${courseId ? 'Chỉnh sửa' : 'Tạo'} khóa học</h3>
          <form id="course-form">
            <input type="hidden" name="id" value="${course.id || ''}">
            <div><label>Tiêu đề</label><input name="title" value="${course.title || ''}" required></div>
            <div><label>Danh mục</label><select name="category_id"></select></div>
            <div><label>Giá</label><input name="price" value="${course.price || ''}"></div>
            <div><label>Mô tả ngắn</label><textarea name="short_desc">${course.short_desc || ''}</textarea></div>
            <div><label>Mô tả</label><textarea name="description">${course.description || ''}</textarea></div>
            <div><button id="save-course-btn" type="button">Lưu</button></div>
          </form>
        `;
        if (!utils.qs('#course-editor')) document.body.appendChild(editor);
        // load categories into select
        const cats = await api.get('/categories');
        const sel = utils.qs('select[name="category_id"]', editor);
        sel.innerHTML = `<option value="">Chọn danh mục</option>` + (cats.map(c => `<option value="${c.id}" ${course.category_id==c.id ? 'selected' : ''}>${c.name}</option>`).join(''));
      } catch (err) {
        ui.notify(err.message || 'Lỗi khi mở form', 'error');
      }
    },
    async saveCourse() {
      try {
        const form = utils.qs('#course-form');
        if (!form) return ui.notify('Không có form', 'error');
        const data = Object.fromEntries(new FormData(form).entries());
        // server expects JSON with course fields
        if (data.id) {
          await api.put(`/instructor/courses/${data.id}`, data);
          ui.notify('Cập nhật khóa học thành công', 'success');
        } else {
          await api.post('/instructor/courses', data);
          ui.notify('Tạo khóa học thành công', 'success');
        }
        this.loadMyCourses();
        // close editor
        const editor = utils.qs('#course-editor');
        if (editor) editor.remove();
      } catch (err) {
        ui.notify(err.message || 'Lỗi khi lưu khóa học', 'error');
      }
    },
    async deleteCourse(courseId) {
      try {
        if (!confirm('Xác nhận xóa khóa học?')) return;
        await api.del(`/instructor/courses/${courseId}`);
        ui.notify('Xóa thành công', 'success');
        this.loadMyCourses();
      } catch (err) {
        ui.notify(err.message || 'Xóa thất bại', 'error');
      }
    },
    async openUploadDialog(lessonId) {
      // simple upload input
      const input = utils.el('input', { type: 'file', id: 'upload-material' });
      input.addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (!file) return;
        const fd = new FormData();
        fd.append('file', file);
        fd.append('lesson_id', lessonId);
        try {
          const res = await api.post('/instructor/materials', fd);
          ui.notify('Tải tài liệu thành công', 'success');
        } catch (err) {
          ui.notify(err.message || 'Tải tài liệu thất bại', 'error');
        } finally {
          input.remove();
        }
      });
      document.body.appendChild(input);
      input.click();
    },
    async viewEnrolledStudents(courseId) {
      try {
        const data = await api.get(`/instructor/courses/${courseId}/students`);
        ui.renderStudentsTable('#students-container', data.students || []);
      } catch (err) {
        ui.notify(err.message || 'Lỗi khi tải danh sách học viên', 'error');
      }
    },
    async trackStudentProgress(courseId, studentId) {
      try {
        const data = await api.get(`/instructor/courses/${courseId}/students/${studentId}/progress`);
        // render detail
        const container = utils.qs('#student-progress');
        if (container) container.innerHTML = `<p>Tiến độ: ${data.progress || '0%'}</p>`;
      } catch (err) {
        ui.notify(err.message || 'Lỗi khi lấy tiến độ', 'error');
      }
    }
  };

  // --- Admin Module ---
  const admin = {
    init() {
      // load users, categories, stats
      this.loadUsers();
      this.loadCategories();
      this.loadStats();

      document.addEventListener('click', async (e) => {
        if (e.target.matches('.btn-toggle-user')) {
          const id = e.target.dataset.id;
          const active = e.target.dataset.active === 'true';
          await this.toggleUser(id, !active);
        } else if (e.target.matches('#refresh-stats')) {
          await this.loadStats();
        } else if (e.target.matches('.btn-approve-course')) {
          const id = e.target.dataset.id;
          await this.approveCourse(id);
        } else if (e.target.matches('.btn-reject-course')) {
          const id = e.target.dataset.id;
          await this.rejectCourse(id);
        }
      });
    },
    async loadUsers() {
      try {
        const res = await api.get('/admin/users');
        ui.renderUsersTable('#admin-users', res.users || []);
      } catch (err) {
        ui.notify(err.message || 'Lỗi khi tải người dùng', 'error');
      }
    },
    async toggleUser(userId, enable = true) {
      try {
        await api.post(`/admin/users/${userId}/toggle`, { active: enable });
        ui.notify('Cập nhật trạng thái người dùng thành công', 'success');
        this.loadUsers();
      } catch (err) {
        ui.notify(err.message || 'Cập nhật thất bại', 'error');
      }
    },
    async loadCategories() {
      try {
        const res = await api.get('/admin/categories');
        const target = utils.qs('#admin-categories');
        if (!target) return;
        target.innerHTML = '<ul>' + (res.categories || []).map(c => `<li>${c.name} <button class="btn-edit-cat" data-id="${c.id}">Sửa</button></li>`).join('') + '</ul>';
      } catch (err) {
        ui.notify(err.message || 'Lỗi khi tải danh mục', 'error');
      }
    },
    async loadStats() {
      try {
        const res = await api.get('/admin/stats');
        const target = utils.qs('#admin-stats');
        if (!target) return;
        target.innerHTML = `
          <div>Users: ${res.users_count || 0}</div>
          <div>Courses: ${res.courses_count || 0}</div>
          <div>Active enrollments: ${res.active_enrollments || 0}</div>
        `;
      } catch (err) {
        ui.notify(err.message || 'Lỗi khi tải thống kê', 'error');
      }
    },
    async approveCourse(courseId) {
      try {
        await api.post(`/admin/courses/${courseId}/approve`, {});
        ui.notify('Duyệt khóa học thành công', 'success');
        // maybe refresh list
      } catch (err) {
        ui.notify(err.message || 'Duyệt thất bại', 'error');
      }
    },
    async rejectCourse(courseId) {
      try {
        await api.post(`/admin/courses/${courseId}/reject`, {});
        ui.notify('Từ chối khóa học', 'success');
        // maybe refresh list
      } catch (err) {
        ui.notify(err.message || 'Từ chối thất bại', 'error');
      }
    }
  };

  // --- Public init ---
  function init(options = {}) {
    ui.init(options.container || '#app');
    // Auto init modules if corresponding containers exist
    if (utils.qs('#course-search-form') || utils.qs('#courses-container')) student.init(options);
    if (utils.qs('#instructor-area') || utils.qs('#instructor-courses')) instructor.init(options);
    if (utils.qs('#admin-area') || utils.qs('#admin-users')) admin.init(options);
    // global delegations (example: open mobile menu)
    document.addEventListener('click', (e) => {
      if (e.target.matches('#btn-refresh-courses')) student.searchCourses({});
    });
  }

  return { init, api, ui, utils, student, instructor, admin };
})();

// Auto init on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  App.init();
});
