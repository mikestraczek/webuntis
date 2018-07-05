<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\Substitutions;
use Webuntis\Models\Teachers;
use Webuntis\Models\Students;
use Webuntis\Models\Classes;
use Webuntis\Models\Subjects;
use Webuntis\Configuration\WebuntisConfiguration;

final class SubstitutionsTest extends TestCase
{
    public function setUp() : void 
    {
        $config = new WebuntisConfiguration([ 
            'default' => [
                   //f.e. thalia, cissa etc.
                    'server' => 'yourserver',
                    'school' => 'yourschool',
                    'username' => 'yourusername',
                    'password' => 'yourpassword'
                ],
            'admin' => [
                   //f.e. thalia, cissa etc.
                    'server' => 'yourserver',
                    'school' => 'yourschool',
                    'username' => 'youradminusername',
                    'password' => 'youradminpassword'
            ],
            'security_manager' => 'Webuntis\Tests\TestSecurityManager'
        ]);
    }

    public function testCreate() : void
    {   
        $data = [
            'id' => 1,
            'startTime' => '800',
            'endTime' => '850',
            'date' => '20180706',
            'lsid' => 1,
            'txt' => 'another teacher',
            'type' => 'cancel',
            'kl' => [
                [
                    'id' => 1
                ]
            ],
            'te' => [
                [
                    'id' => 1
                ]
            ],
            'ro' => [
                [
                    'id' => 1
                ]
            ],
            'su' => [
                [
                    'id' => 1
                ]
            ]
        ];

        $sub = new Substitutions($data);
        $this->assertEquals(1, $sub->getId());
        $this->assertEquals(1, $sub->getSubjects()[0]->getId());
        $this->assertEquals(new \DateTime('2018-07-06 8:00'), $sub->getStartTime());
        $this->assertEquals(new \DateTime('2018-07-06 8:50'), $sub->getEndTime());
        $this->assertEquals(1, $sub->getTeachers()[0]->getId());
        $this->assertEquals(1, $sub->getClasses()[0]->getId());
        $this->assertEquals(1, $sub->getRooms()[0]->getId());
        $this->assertEquals(1, $sub->getLesson()->getId());
        $this->assertEquals('another teacher', $sub->getText());
        $this->assertEquals('cancel', $sub->getType());

        $expected = [
            'id' => 1,
            'startTime' => '2018-07-06T08:00:00+0200',
            'endTime' => '2018-07-06T08:50:00+0200',
            'classes' => [
                [
                    'id' => 1,
                    'name' => 'test',
                    'fullName' => 'teststring'
                ]
            ],
            'teachers' => [
                [
                    'id' => 1,
                    'name' => 'asdman',
                    'firstName' => 'asd',
                    'lastName' => 'man'
                ]
            ],
            'rooms' => [
                [
                    'id' => 1,
                    'name' => '210',
                    'fullName' => 'Second Floor'
                ]
            ],
            'subjects' => [
                [
                    'id' => 1,
                    'name' => 'en',
                    'fullName' => 'english'
                ]
            ],
            'lesson' => [
                'id' => 1,
                'startTime' => '2018-07-03T08:00:00+0200',
                'endTime' => '2018-07-03T08:50:00+0200',
                'classes' => [
                    [
                        'id' => 1,
                        'name' => 'test',
                        'fullName' => 'teststring'
                    ]
                ],
                'teachers' => [
                    [
                        'id' => 1,
                        'name' => 'asdman',
                        'firstName' => 'asd',
                        'lastName' => 'man'
                    ]
                ],
                'subjects' => [
                    [
                        'id' => 1,
                        'name' => 'en',
                        'fullName' => 'english'
                    ]
                ],
                'rooms' => [
                    [
                        'id' => 1,
                        'name' => '210',
                        'fullName' => 'Second Floor'
                    ]
                ],
                'code' => 'normal',
                'type' => 'lesson'
            ],
            'type' => 'cancel',
            'text' => 'another teacher'
        ];

        $this->assertEquals($expected, $sub->serialize());
    }
}
