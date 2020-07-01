<?php

use Illuminate\Database\Seeder;
use App\TherapyQuestionnaire;
use App\TherapyQuestionnaireAnswer;

class TherapyQuestionnaireTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TherapyQuestionnaire::insert([
            'id'          => 1,
            'placeholder' => 'Your Height',
            'title'       => 'Your Height',
            'type'        => 'number',
            'min'         => '2',
            'max'         => '8'
        ]);

        TherapyQuestionnaire::insert([
            'id'          => 2,
            'placeholder' => 'Your Weight',
            'title'       => 'Your Weight',
            'type'        => 'number',
            'min'         => '10',
            'max'         => '100'
        ]);

        TherapyQuestionnaire::insert([
            'id'          => 3,
            'placeholder' => 'Do you practice any sports ?',
            'title'       => 'Do you practice any sports ?',
            'type'        => 'text',
            'min'         => '',
            'max'         => ''
        ]);

        TherapyQuestionnaire::insert([
            'id'          => 4,
            'placeholder' => 'What is your life style ?',
            'title'       => 'What is your life style ?',
            'type'        => 'text',
            'min'         => '',
            'max'         => ''
        ]);

        TherapyQuestionnaire::insert([
            'id'          => 5,
            'placeholder' => 'What is your type of diet ?',
            'title'       => 'What is your type of diet ?',
            'type'        => 'text',
            'min'         => '',
            'max'         => ''
        ]);

        TherapyQuestionnaire::insert([
            'id'          => 6,
            'placeholder' => 'Do you have any nutritional problems ?',
            'title'       => 'Do you have any nutritional problems ?',
            'type'        => 'text',
            'min'         => '',
            'max'         => ''
        ]);

        TherapyQuestionnaire::insert([
            'id'          => 7,
            'placeholder' => 'Do you take any supplements ?',
            'title'       => 'Do you take any supplements ?',
            'type'        => 'text',
            'min'         => '',
            'max'         => ''
        ]);

        TherapyQuestionnaire::insert([
            'id'          => 8,
            'placeholder' => 'Current meditations or therapies ?',
            'title'       => 'Current meditations or therapies ?',
            'type'        => 'text',
            'min'         => '',
            'max'         => ''
        ]);

        TherapyQuestionnaire::insert([
            'id'          => 9,
            'placeholder' => 'Any diseases and health conditions ?',
            'title'       => 'Any diseases and health conditions ?',
            'type'        => 'text',
            'min'         => '',
            'max'         => ''
        ]);

        TherapyQuestionnaire::insert([
            'id'          => 10,
            'placeholder' => 'Psychological or emotional issues ?',
            'title'       => 'Psychological or emotional issues ?',
            'type'        => 'text',
            'min'         => '',
            'max'         => ''
        ]);

        TherapyQuestionnaire::insert([
            'id'          => 11,
            'placeholder' => 'Do you feel a lack or excess energy ?',
            'title'       => 'Do you feel a lack or excess energy ?',
            'type'        => 'text',
            'min'         => '',
            'max'         => ''
        ]);

        TherapyQuestionnaire::insert([
            'id'          => 12,
            'placeholder' => 'Do you have any bad habits ?',
            'title'       => 'Do you have any bad habits ?',
            'type'        => 'text',
            'min'         => '',
            'max'         => ''
        ]);
    }
}
