<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentBackup extends Model
{
    protected $table = 'core_student_backups';
    protected $guarded = ['id'];

    public function backupBy()
    {
        return $this->belongsTo(User::class, 'backup_by');
    }
    
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id')->withTrashed();
    }
}
