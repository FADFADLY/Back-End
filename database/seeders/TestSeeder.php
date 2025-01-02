<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;

class TestSeeder extends Seeder
{
    public function run()
    {
        $tests =
            [
                [
                    'name' => 'Taylor test for anxiety and depression',
                    'description' => 'A test designed to evaluate anxiety and depression symptoms.',
                    'questions' => [
                        [
                            'question' => 'I do not tire quickly',
                            'answers' => [['answer' => 'True', 'points' => 0], ['answer' => 'False', 'points' => 1]]
                        ],
                        [
                            'question' => 'I believe I am no more nervous than others',
                            'answers' => [['answer' => 'True', 'points' => 0], ['answer' => 'False', 'points' => 1]]
                        ],
                        [
                            'question' => 'I have very few headaches',
                            'answers' => [['answer' => 'True', 'points' => 0], ['answer' => 'False', 'points' => 1]]
                        ],
                        [
                            'question' => 'I work under a great deal of tension',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I frequently notice my hand shakes when I try do something',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I blush no more often than others',
                            'answers' => [['answer' => 'True', 'points' => 0], ['answer' => 'False', 'points' => 1]]
                        ],
                        [
                            'question' => 'I have diarrhea one a month or more',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I worry quite a bit over possible misfortunes',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I practically never blush',
                            'answers' => [['answer' => 'True', 'points' => 0], ['answer' => 'False', 'points' => 1]]
                        ],
                        [
                            'question' => 'I am often afraid that I am going to blush',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'My hands and feet are usually warm enough',
                            'answers' => [['answer' => 'True', 'points' => 0], ['answer' => 'False', 'points' => 1]]
                        ],
                        [
                            'question' => 'I sweat very easily even on cool days',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'Sometimes when embarrassed, I break out in a sweat',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I hardly ever notice my heart pounding, and I am seldom short of breath',
                            'answers' => [['answer' => 'True', 'points' => 0], ['answer' => 'False', 'points' => 1]]
                        ],
                        [
                            'question' => 'I feel hungry almost all of the time',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I am very seldom troubled by constipation',
                            'answers' => [['answer' => 'True', 'points' => 0], ['answer' => 'False', 'points' => 1]]
                        ],
                        [
                            'question' => 'I have a great deal of stomach trouble',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I have had periods in which I lost sleep over worry',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I am easily embarrassed',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I am more sensitive than most other people',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I frequently find myself worrying about something',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I wish I could be as happy as others seem to be',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I am usually calm and not easily upset',
                            'answers' => [['answer' => 'True', 'points' => 0], ['answer' => 'False', 'points' => 1]]
                        ],
                        [
                            'question' => 'I feel anxiety about something or someone almost all of the time',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I am happy most of the time',
                            'answers' => [['answer' => 'True', 'points' => 0], ['answer' => 'False', 'points' => 1]]
                        ],
                        [
                            'question' => 'It makes me nervous to have to wait',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'Sometimes I become so excited I find it hard to get to sleep',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I have sometimes felt that difficulties piling up so high I couldn’t get over them',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I admit I have felt worried beyond reason over small things',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I have very few fears compared to my friends',
                            'answers' => [['answer' => 'True', 'points' => 0], ['answer' => 'False', 'points' => 1]]
                        ],
                        [
                            'question' => 'I certainly feel useless at times',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I find it hard to keep my mind on a task or job',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I am usually self-conscious',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I am inclined to take things hard',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'At times I think I am no good at all',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I am certainly lacking in self-confidence',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I sometimes feel that I am about to go to pieces',
                            'answers' => [['answer' => 'True', 'points' => 1], ['answer' => 'False', 'points' => 0]]
                        ],
                        [
                            'question' => 'I am entirely self-confident',
                            'answers' => [['answer' => 'True', 'points' => 0], ['answer' => 'False', 'points' => 1]]
                        ]

                    ]
                ],
                [
                    'name' => 'Beck test for depression',
                    'description' => 'A comprehensive test to measure levels of depression.',
                    'questions' => [
                        [
                            'question' => 'I do not feel sad.',
                            'answers' => [
                                ['answer' => 'I do not feel sad.', 'points' => 0],
                                ['answer' => 'I feel sad.', 'points' => 1],
                                ['answer' => 'I am sad all the time and I can\'t snap out of it.', 'points' => 2],
                                ['answer' => 'I am so sad and unhappy that I can\'t stand it.', 'points' => 3],
                            ]
                        ],
                        [
                            'question' => 'I am not particularly discouraged about the future.',
                            'answers' => [
                                ['answer' => 'I am not particularly discouraged about the future.', 'points' => 0],
                                ['answer' => 'I feel discouraged about the future.', 'points' => 1],
                                ['answer' => 'I feel I have nothing to look forward to.', 'points' => 2],
                                ['answer' => 'I feel the future is hopeless and that things cannot improve.', 'points' => 3],
                            ]
                        ],
                        [
                            'question' => 'I do not feel like a failure.',
                            'answers' => [
                                ['answer' => 'I do not feel like a failure.', 'points' => 0],
                                ['answer' => 'I feel I have failed more than the average person.', 'points' => 1],
                                ['answer' => 'As I look back on my life, all I can see is a lot of failures.', 'points' => 2],
                                ['answer' => 'I feel I am a complete failure as a person.', 'points' => 3],
                            ]
                        ],
                        [
                            'question' => 'I get as much satisfaction out of things as I used to.',
                            'answers' => [
                                ['answer' => 'I get as much satisfaction out of things as I used to.', 'points' => 0],
                                ['answer' => 'I don\'t enjoy things the way I used to.', 'points' => 1],
                                ['answer' => 'I don\'t get real satisfaction out of anything anymore.', 'points' => 2],
                                ['answer' => 'I am dissatisfied or bored with everything.', 'points' => 3],
                            ]
                        ],
                        [
                            'question' => 'I don\'t feel particularly guilty.',
                            'answers' => [
                                ['answer' => 'I don\'t feel particularly guilty.', 'points' => 0],
                                ['answer' => 'I feel guilty a good part of the time.', 'points' => 1],
                                ['answer' => 'I feel quite guilty most of the time.', 'points' => 2],
                                ['answer' => 'I feel guilty all of the time.', 'points' => 3],
                            ]
                        ],
                        [
                            'question' => 'I don\'t feel I am being punished.',
                            'answers' => [
                                ['answer' => 'I don\'t feel I am being punished.', 'points' => 0],
                                ['answer' => 'I feel I may be punished.', 'points' => 1],
                                ['answer' => 'I expect to be punished.', 'points' => 2],
                                ['answer' => 'I feel I am being punished.', 'points' => 3],
                            ]
                        ],
                        [
                            'question' => 'I don\'t feel disappointed in myself.',
                            'answers' => [
                                ['answer' => 'I don\'t feel disappointed in myself.', 'points' => 0],
                                ['answer' => 'I am disappointed in myself.', 'points' => 1],
                                ['answer' => 'I am disgusted with myself.', 'points' => 2],
                                ['answer' => 'I hate myself.', 'points' => 3],
                            ]
                        ],
                        [
                            'question' => 'I don\'t feel I am any worse than anybody else.',
                            'answers' => [
                                ['answer' => 'I don\'t feel I am any worse than anybody else.', 'points' => 0],
                                ['answer' => 'I am critical of myself for my weaknesses or mistakes.', 'points' => 1],
                                ['answer' => 'I blame myself all the time for my faults.', 'points' => 2],
                                ['answer' => 'I blame myself for everything bad that happens.', 'points' => 3],
                            ]
                        ],
                        [
                            'question' => 'I don\'t have any thoughts of killing myself.',
                            'answers' => [
                                ['answer' => 'I don\'t have any thoughts of killing myself.', 'points' => 0],
                                ['answer' => 'I have thoughts of killing myself, but I would not carry them out.', 'points' => 1],
                                ['answer' => 'I would like to kill myself.', 'points' => 2],
                                ['answer' => 'I would kill myself if I had the chance.', 'points' => 3],
                            ]
                        ],
                        [
                            'question' => 'I don\'t cry any more than usual.',
                            'answers' => [
                                ['answer' => 'I don\'t cry any more than usual.', 'points' => 0],
                                ['answer' => 'I cry more now than I used to.', 'points' => 1],
                                ['answer' => 'I cry all the time now.', 'points' => 2],
                                ['answer' => 'I used to be able to cry, but now I can\'t cry even though I want to.', 'points' => 3],
                            ]
                        ],
                        [
                            'question' => 'I am no more irritated by things than I ever was.',
                            'answers' => [
                                ['answer' => 'I am no more irritated by things than I ever was.', 'points' => 0],
                                ['answer' => 'I am slightly more irritated now than usual.', 'points' => 1],
                                ['answer' => 'I am quite annoyed or irritated a good deal of the time.', 'points' => 2],
                                ['answer' => 'I feel irritated all the time.', 'points' => 3],
                            ]
                        ],
                        [
                            'question' => 'I have not lost interest in other people.',
                            'answers' => [
                                ['answer' => 'I have not lost interest in other people.', 'points' => 0],
                                ['answer' => 'I am less interested in other people than I used to be.', 'points' => 1],
                                ['answer' => 'I have lost most of my interest in other people.', 'points' => 2],
                                ['answer' => 'I have lost all of my interest in other people.', 'points' => 3],
                            ]
                        ],
                        [
                            'question' => 'I make decisions about as well as I ever could.',
                            'answers' => [
                                ['answer' => 'I make decisions about as well as I ever could.', 'points' => 0],
                                ['answer' => 'I put off making decisions more than I used to.', 'points' => 1],
                                ['answer' => 'I have greater difficulty in making decisions more than I used to.', 'points' => 2],
                                ['answer' => 'I can\'t make decisions at all anymore.', 'points' => 3],
                            ]
                        ],
                    ]
                ]
            ];


        foreach ($tests as $testData) {
            $test = Test::create([
                'name'        => $testData['name'],
                'description' => $testData['description'],
            ]);
            $this->seedQuestions($test, $testData['questions']);
        }
    }

    private function seedQuestions($test, array $questions)
    {
        foreach ($questions as $questionData) {
            $question = $test->questions()->create([
                'question' => $questionData['question'],
            ]);
            $this->seedAnswers($question, $questionData['answers']);
        }
    }

    private function seedAnswers($question, array $answers)
    {
        foreach ($answers as $answerData) {
            $question->answers()->create([
                'answer' => $answerData['answer'],
                'points' => $answerData['points'],
            ]);
        }
    }
}
