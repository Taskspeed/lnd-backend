<?php

namespace App\Models\Forms\LPR;

use Illuminate\Database\Eloquent\Model;

class LearnerProgressForm extends Model
{
    //
    protected $table = 'learner_progress_forms';

    protected $fillable = [
        'event_id',
        'forms_name',
        'control_no',
        'learner',
        'lnd_attended',
        'date_of_attendance',
        'delivering_service_excellence_competency',
        'exemplifying_integrity_competency',
        'interpersonal_skills_competency',
        'planning_organizing_competency',
        'records_management_competency',
        'partnering_networking_competency',
        'process_management_competency',
        'managing_performance_coaching_results_competency',
        'building_collaborative_inclusive_working_relationships_competency',
        'thinking_strategically_creatively_competency',
        'problem_solving_decision_making_competency',
        'remarks'
    ];

    protected $appends = ['learner_progress_form_id'];
    protected $hidden = ['id','created_at','updated_at'];

    public function getLearnerProgressFormsIdAttribute()
    {
        return $this->id;
    }

    protected $casts = [
        'delivering_service_excellence_competency' => 'integer',
         'exemplifying_integrity_competency'=> 'integer',
        'interpersonal_skills_competency'=> 'integer',
        'planning_organizing_competency'=> 'integer',
        'records_management_competency'=> 'integer',
        'partnering_networking_competency'=> 'integer',
        'process_management_competency'=> 'integer',
        'managing_performance_coaching_results_competency'=> 'integer',
        'building_collaborative_inclusive_working_relationships_competency' => 'integer',
        'thinking_strategically_creatively_competency'=> 'integer',
        'problem_solving_decision_making_competency'=> 'integer',
        'date_of_attendance' => 'date:F d, Y',
    ];

    public function coreProgress(){
        return  $this->hasOne(CoreProgress::class);
    }

     public function leaderShipProgress(){
        return  $this->hasOne(LeadershipProgress::class);
    }

     public function technicalProgress(){
        return  $this->hasOne(TechnicalProgress::class);
    }
}
