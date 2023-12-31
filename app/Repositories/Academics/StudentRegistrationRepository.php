<?php

namespace App\Repositories\Academics;

 use Auth; 
 use App\Models\Admissions\{ Student };
 use App\Models\Enrollments\{ Enrollment };
 use App\Models\Academics\{ Course, AcademicPeriodClass, AcademicPeriodInformation, CourseLevel, ProgramCourses, Grade, Prerequisite };

class StudentRegistrationRepository
{

    public function getStudent($student_id = null)
    {
        
        if($student_id){

            // incase request from management
            $student = Student::find($student_id);

        } else {
            // incase request from student
            $student = Student::where('user_id', Auth::user()->id)->first();
        }

        return $student;
    }

    public function getAll($student_id = null)
    {

        // step 1 - get student
        if($student_id){

            // incase request from management
            $student = Student::find($student_id);

        } else {

            // incase request from student
            $student = $this->getStudent();
        }

        // step 2 - get available courses for that academic period with running classes

                // get courses with prerequisites
                $courses = ProgramCourses::join('courses', 'courses.id', 'program_courses.course_id')
                                            ->where('program_id', $student->program_id)
                                            ->where('course_level_id', $student->course_level_id )
                                            ->get();
                
                // get academic information
                $academicInfo = AcademicPeriodInformation::where('study_mode_id', $student->study_mode_id)->where('academic_period_intake_id', $student->academic_period_intake_id)->first();

                // get the academic period id
                $currentAcademicPeriodId = $academicInfo->academic_period_id;

                // match courses for a specific academic period
                $currentCourses = $courses->filter(function ($course) use ($currentAcademicPeriodId) {
                    return AcademicPeriodClass::where('course_id', $course->id)
                                ->where('academic_period_id', $currentAcademicPeriodId)
                                ->exists();
                });

                // get academic class info
                $courseIds = $currentCourses->pluck('course_id')->toArray();
            
                $currentCourses = AcademicPeriodClass::join('courses', 'courses.id', 'academic_period_classes.course_id')
                                ->whereIn('course_id', $courseIds)
                                ->where('academic_period_id', $currentAcademicPeriodId)
                                ->get(['code', 'name', 'course_id', 'academic_period_classes.id']);





                /*

        // step 3 - get all courses that student failed in previous academic periods

        $failedCourses = Grade::where('student_id', $student->id)
        ->where('academic_period_id', $academicInfo->academic_period_id)
        ->where('total', '<', 40) 
        ->get();

        // step 4 - check if failed courses are prerequisites of propoesed student current courses
        
                // extract course ids
                $currentCourseIds = $currentCourses->pluck('course_id')->toArray();

                // get current course prerequisites
                $currentCoursePrerequisites = Prerequisite::whereIn('course_id', $currentCourseIds)->get();

                // check if any failed course is a prerequisite 
                $failedCourseIds = $failedCourses->pluck('course_id')->toArray();

                $failedPrerequisites = $currentCoursePrerequisites->filter(function ($prerequisite) use ($failedCourseIds) {
                    return in_array($prerequisite->prerequisite_course_id, $failedCourseIds);
                });

                $failedPrerequisitesCourseIds = $failedPrerequisites->pluck('prerequisite_course_id')->toArray();

                if ($failedPrerequisites->isNotEmpty()) {

                    // step 5 - check if failed course is currently running in current academic period , if it is add it to current courses

                    $isFailedCourseRunning = AcademicPeriodClass::whereIn('course_id', $failedPrerequisitesCourseIds)
                                                                ->where('academic_period_id', $currentAcademicPeriodId)
                                                                ->get();


                    
                    if($isFailedCourseRunning){



                    } 

                // Remove the current courses that have failed course as prerequisite
                $currentCourses = $currentCourses->reject(function ($course) use ($failedPrerequisites) {
                    return $failedPrerequisites->contains('course_id', $course->id);
                });


                } else {

                    return $currentCourses;
                }

        
*/


        return $currentCourses;

    } 
    

    public function getAcademicInfo($student_id = null)
    {
        if($student_id) {

            // incase request from management
            $student = $this->getStudent($student_id);

        } else {

            // incase request from student
            $student = $this->getStudent();
        }
        

        $academicInfo = $student->academic_info()
            ->with(['academic_period', 'study_mode'])
            ->first();
    
        return $academicInfo;
    }

    public function getRegisterationStatus($student_id = null)
    {
        // get courses
        $courses = $this->getAll($student_id);
        $classIds = $courses->pluck('id')->toArray();

        // check if student has already been enrolled in courses
        $enrollmentExists = Enrollment::whereIn('academic_period_class_id', $classIds)->exists();

        return $enrollmentExists;
  
    }

}
