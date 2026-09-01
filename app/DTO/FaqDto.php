<?php

namespace App\DTO;

class FaqDto
{
    public $question;
    public $question_hi;
    public $answer;
    public $answer_hi;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $updated_by;

    public function __construct(
        $question,
        $question_hi,
        $answer,
        $answer_hi,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $publish_remark = null,
        $created_by,
        $updated_by = null
    ) {
        $this->question = $question;
        $this->question_hi = $question_hi;
        $this->answer = $answer;
        $this->answer_hi = $answer_hi;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->remarks = $remarks;
        $this->publish_remark = $publish_remark;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }

    public function toArray(): array
    {
        return [
            'question' => $this->question,
            'question_hi' => $this->question_hi,
            'answer' => $this->answer,
            'answer_hi' => $this->answer_hi,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
