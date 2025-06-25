<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolPanel\Setting\SessionyearController;
use App\Http\Controllers\SchoolPanel\Setting\LevelController;
use App\Http\Controllers\SchoolPanel\Setting\FacultyController;
use App\Http\Controllers\SchoolPanel\Setting\ProgramyearController;
use App\Http\Controllers\SchoolPanel\Setting\DepartmentController;
use App\Http\Controllers\SchoolPanel\Setting\SectionController;
use App\Http\Controllers\SchoolPanel\Setting\ReligionController;
use App\Http\Controllers\SchoolPanel\Setting\ExamController;
use App\Http\Controllers\SchoolPanel\Setting\SubjectController;
use App\Http\Controllers\SchoolPanel\Setting\DesignationController;
use App\Http\Controllers\SchoolPanel\Setting\MarkinfoController;
use App\Http\Controllers\SchoolPanel\Setting\EmployeePermissionController;

   

      Route::middleware('auth:sanctum')->group(function () {

             Route::get('/{school_username}/religion', [ReligionController::class, 'religion']);

             Route::get('/{school_username}/sessionyear', [SessionyearController::class, 'sessionyear']);

             Route::get('/{school_username}/programyear', [ProgramyearController::class, 'programyear']);

             Route::get('/{school_username}/programyear', [ProgramyearController::class, 'programyear']);

            Route::get('/{school_username}/level', [LevelController::class, 'level']);

            Route::get('/{school_username}/faculty', [FacultyController::class, 'faculty']);

            Route::get('/{school_username}/department', [DepartmentController::class, 'department']);

            Route::get('/{school_username}/section', [SectionController::class, 'section']);

              Route::get('/{school_username}/exam', [ExamController::class, 'exam']);
              Route::get('/{school_username}/subject', [SubjectController::class, 'subject']);
              Route::get('/{school_username}/designation', [DesignationController::class, 'designation']);
              Route::get('/{school_username}/markinfo', [MarkinfoController::class, 'markinfo']);
              Route::get('/{school_username}/permission_role', [EmployeePermissionController::class, 'permission_role']);
              Route::get('/{school_username}/permission', [EmployeePermissionController::class, 'permission']);


            Route::middleware('SettingMiddleware:{school_username}')->group(function(){ 

                 // religion
                
                 Route::post('/{school_username}/religion-add', [ReligionController::class, 'religion_add']);
                 Route::post('/{school_username}/religion-update/{id}', [ReligionController::class, 'religion_update']);
                 Route::delete('/{school_username}/religion-delete/{id}', [ReligionController::class, 'religion_delete']);

                 // Session
               
                 Route::post('/{school_username}/session-add', [SessionyearController::class, 'session_add']);
                 Route::post('/{school_username}/session-update/{id}', [SessionyearController::class, 'session_update']);
                 Route::delete('/{school_username}/session-delete/{id}', [SessionyearController::class, 'session_delete']);

                 // programyear
                 Route::post('/{school_username}/programyear-add', [ProgramyearController::class, 'programyear_add']);
                 Route::post('/{school_username}/programyear-update/{id}', [ProgramyearController::class, 'programyear_update']);
                 Route::delete('/{school_username}/programyear-delete/{id}', [ProgramyearController::class, 'programyear_delete']);
    
                 // level
                 Route::post('/{school_username}/level-add', [LevelController::class, 'level_add']);
                 Route::post('/{school_username}/level-update/{id}', [LevelController::class, 'level_update']);
                 Route::delete('/{school_username}/level-delete/{id}', [LevelController::class, 'level_delete']);

                 // faculty
                 Route::post('/{school_username}/faculty-add', [FacultyController::class, 'faculty_add']);
                 Route::post('/{school_username}/faculty-update/{id}', [FacultyController::class, 'faculty_update']);
                 Route::delete('/{school_username}/faculty-delete/{id}', [FacultyController::class, 'faculty_delete']);

                 // department
               
                 Route::post('/{school_username}/department-add', [DepartmentController::class, 'department_add']);
                 Route::post('/{school_username}/department-update/{id}', [DepartmentController::class, 'department_update']);
                 Route::delete('/{school_username}/department-delete/{id}', [DepartmentController::class, 'department_delete']);

                  // section 
                
                  Route::post('/{school_username}/section-add', [SectionController::class, 'section_add']);
                  Route::post('/{school_username}/section-update/{id}', [SectionController::class, 'section_update']);
                  Route::delete('/{school_username}/section-delete/{id}', [SectionController::class, 'section_delete']);

                  // exam 
                
                  Route::post('/{school_username}/exam-add', [ExamController::class, 'exam_add']);
                  Route::post('/{school_username}/exam-update/{id}', [ExamController::class, 'exam_update']);
                  Route::delete('/{school_username}/exam-delete/{id}', [ExamController::class, 'exam_delete']);


                  // subject 
                  Route::post('/{school_username}/subject-export', [SubjectController::class, 'subject_export']);
                  Route::post('/{school_username}/subject-import', [SubjectController::class, 'subject_import']);
                  Route::post('/{school_username}/subject-add', [SubjectController::class, 'subject_add']);
                  Route::post('/{school_username}/subject-update/{id}', [SubjectController::class, 'subject_update']);
                  Route::delete('/{school_username}/subject-delete/{id}', [SubjectController::class, 'subject_delete']); 


               // designation
             
               Route::post('/{school_username}/designation-add', [DesignationController::class, 'designation_add']);
               Route::post('/{school_username}/designation-update/{id}', [DesignationController::class, 'designation_update']);
               Route::delete('/{school_username}/designation-delete/{id}', [DesignationController::class, 'designation_delete']);


                //marks Info
             
                Route::post('/{school_username}/markinfo-add', [MarkinfoController::class, 'markinfo_add']);
                Route::post('/{school_username}/markinfo-update/{id}', [MarkinfoController::class, 'markinfo_update']);
                Route::delete('/{school_username}/markinfo-delete/{id}', [MarkinfoController::class, 'markinfo_delete']);

                //Employee  Permission 
           
                Route::post('/{school_username}/permission-add', [employeePermissionController::class, 'permission_add']);
                Route::post('/{school_username}/permission-update/{id}', [employeePermissionController::class, 'permission_update']);
                Route::delete('/{school_username}/permission-delete/{id}', [employeePermissionController::class, 'permission_delete']);

           });
     });

?>