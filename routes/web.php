<?php

use App\Http\Controllers\AccessRoleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\ColumnTitleController;
use App\Http\Controllers\HistoryTryoutController;
use App\Http\Controllers\ListInvoiceController;
use App\Http\Controllers\ListPakcageController;
use App\Http\Controllers\ListStudentController;
use App\Http\Controllers\MedicalFieldController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionBankController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionDetailController;
use App\Http\Controllers\QuestionDetailTypeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubTopicController;
use App\Http\Controllers\TaskHistoryController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TryoutController;
use App\Http\Controllers\UserManagementController;
use App\Http\Middleware\OtpAccessMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route for the auth page
Route::resource('forgot-password', OtpController::class);
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::get('register', [LoginController::class, 'register'])->name('register');
Route::post('login/check', [LoginController::class, 'login'])->name('login.check');
Route::post('register/store', [LoginController::class, 'registerStore'])->name('register.store');
Route::post('logout', [LoginController::class, 'logout'])->name('login.logout');
Route::post('/send-otp', [OtpController::class, 'sendOtp'])->name('send.otp');
Route::get('/verify-otp', [OtpController::class, 'showVerifyOtpPage'])
    ->middleware(OtpAccessMiddleware::class)
    ->name('verify.otp.view');
Route::get('/change-password', [OtpController::class, 'changePasswordPage'])
    ->middleware(OtpAccessMiddleware::class)
    ->name('change.password.view');
Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/change-password', [OtpController::class, 'updatePassword'])->name('change.password');


// Route for the private access
Route::middleware('auth')->resource('profile', ProfileController::class);
Route::middleware('auth')->resource('tryout', TryoutController::class);
Route::middleware('auth')->resource('task-history', TaskHistoryController::class);
Route::middleware('auth')->resource('tryout-archive', HistoryTryoutController::class);
Route::middleware('auth')->resource('list-package', ListPakcageController::class);
Route::middleware('auth')->resource('payment', PaymentController::class);
Route::middleware('auth')->resource('admin/dashboard', AdminController::class);
Route::middleware('auth')->resource('dashboard/student', StudentController::class);
Route::middleware('auth')->resource('admin/access-role', AccessRoleController::class);
Route::middleware('auth')->resource('admin/user-management', UserManagementController::class);
Route::middleware('auth')->resource('admin/list-students', ListStudentController::class);
Route::middleware('auth')->resource('admin/broadcast', BroadcastController::class);
Route::middleware('auth')->resource('admin/question', QuestionController::class);
Route::middleware('auth')->resource('admin/medical-field', MedicalFieldController::class);
Route::middleware('auth')->resource('admin/question-detail', QuestionDetailController::class);
Route::middleware('auth')->resource('admin/sub-topic', SubTopicController::class);
Route::middleware('auth')->resource('admin/topic', TopicController::class);
Route::middleware('auth')->resource('admin/setting', SettingController::class);
Route::middleware('auth')->resource('admin/package', PackageController::class);
Route::middleware('auth')->resource('admin/question-detail-type', QuestionDetailTypeController::class);
Route::middleware('auth')->resource('admin/question-bank', QuestionBankController::class);
Route::middleware('auth')->resource('admin/column-title', ColumnTitleController::class);
Route::middleware('auth')->resource('admin/list-invoice', ListInvoiceController::class);

Route::middleware(['auth'])->get('tryout/{idQuestion}/question', [TryoutController::class, 'index'])->name('tryout.question.detail');

Route::middleware('auth')->get('admin/get/private', [AccessRoleController::class, 'getPrivateAccessRoleData'])->name('admin.access-role.private');
Route::middleware('auth')->get('admin/get/public', [AccessRoleController::class, 'getPublicAccessRoleData'])->name('admin.access-role.public');
Route::middleware('auth')->get('admin/get/menus', [AccessRoleController::class, 'menuData'])->name('admin.access-role.menus');
Route::middleware('auth')->get('admin/get/permission/{id}', [AccessRoleController::class, 'permissionData'])->name('admin.access-role.get.permission');
Route::middleware('auth')->get('broadcast/data/{id}', [BroadcastController::class, 'getNotificationDataSendToUser'])->name('broadcast.data');
Route::middleware('auth')->get('questions/list', [QuestionController::class, 'showQuestion'])->name('question-list.index');
Route::middleware('auth')->get('payment-histories', [PaymentController::class, 'showPaymentHistory'])->name('payment-histories.index');
Route::middleware('auth')->get('questions/preview/{id}', [QuestionController::class, 'showQuestionPreview'])->name('question.preview');
Route::middleware('auth')->get('questions/detail/edit/{id}', [QuestionDetailController::class, 'getQuestionDetailById'])->name('question-detail.detail.edit');
Route::middleware('auth')->get('/package/search/question', [PackageController::class, 'searchQuestions'])->name('package.search.question');
Route::middleware('auth')->get('package/{id}/selected-questions', [PackageController::class, 'getSelectedQuestions'])->name('package.getSelectedQuestions');
Route::middleware('auth')->get('/admin/search-question-bank', [QuestionBankController::class, 'searchQuestionBank'])->name('admin.searchQuestionBank');


Route::middleware('auth')->post('admin/access-role/data', [AccessRoleController::class, 'getAccessRoleData'])->name('admin.access-role.data');
Route::middleware('auth')->post('admin/access-role/save/permission', [AccessRoleController::class, 'saveOrUpdatePermission'])->name('admin.access-role.permission');
Route::middleware('auth')->post('admin/user-management', [UserManagementController::class, 'getPrivateUserData'])->name('admin.user-management.private');
Route::middleware('auth')->post('admin/list-student', [ListStudentController::class, 'getPublicUserData'])->name('admin.list-student.public');
Route::middleware('auth')->post('admin/broadcast/table', [BroadcastController::class, 'getNotificationData'])->name('admin.broadcast.table');
Route::middleware('auth')->post('broadcast/read/', [BroadcastController::class, 'readNotification'])->name('broadcast.read');
Route::middleware('auth')->post('admin/question/table', [QuestionController::class, 'getQuestionData'])->name('question.table');
Route::middleware('auth')->post('admin/question/upload_image', [QuestionController::class, 'uploadImage'])->name('admin.question.upload_image');
Route::middleware('auth')->post('admin/question/upload_file', [QuestionController::class, 'uploadFile'])->name('admin.question.upload_file');
Route::middleware('auth')->post('admin/question/dropdown', [QuestionController::class, 'getQuestionByName'])->name('question.get-questions');
Route::middleware('auth')->post('admin/question/attach-question-detail', [QuestionController::class, 'attachQuestionDetails'])->name('question.attach-question-detail');
Route::middleware('auth')->post('admin/question/detach-question-detail', [QuestionController::class, 'detachQuestionDetails'])->name('question.detach-question-detail');
Route::middleware('auth')->post('admin/medical-fields/table', [MedicalFieldController::class, 'getMedicalFields'])->name('admin.medical-fields.table');
Route::middleware('auth')->post('admin/medical-fields/dropdown', [MedicalFieldController::class, 'getMedicalFieldByName'])->name('admin.medical-fields.dropdown');
Route::middleware('auth')->post('admin/topic/table', [TopicController::class, 'getTopicTable'])->name('admin.topic.table');
Route::middleware('auth')->post('admin/topic/data', [TopicController::class, 'getTopicData'])->name('admin.topic.data');
Route::middleware('auth')->post('admin/sub-topic/table', [SubTopicController::class, 'getSubTopicTable'])->name('admin.sub-topic.table');
Route::middleware('auth')->post('/update-password/{id}', [ProfileController::class, 'updatePassword'])->name('update.password');
Route::middleware('auth')->post('tryout/history/answer', [TryoutController::class, 'getHistoryAnswer'])->name('tryout.history.answer');



Route::middleware('auth')->delete('/package/{package}/question/{question}', [PackageController::class, 'destroyQuestion'])
->name('package.question.destroy');
