<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace Webuntis\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\Configuration\YAMLConfiguration;
use Webuntis\Models\Interfaces\CachableModelInterface;
use Webuntis\Query\Query;

/**
 * Class CacheBuildCommand
 * @package Webuntis\Command
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class CacheBuildCommand extends Command{
    protected function configure() {
        $this->setName('webuntis:cache:build')
            ->setDescription('builds the webuntis cache')
            ->setHelp('This Command builds the webuntis cache')
            ->addArgument('server', InputArgument::OPTIONAL, 'server whch the school is based in')
            ->addArgument('school', InputArgument::OPTIONAL, 'school')
            ->addArgument('adminusername', InputArgument::OPTIONAL, 'the admin username')
            ->addArgument('adminpassword', InputArgument::OPTIONAL, 'the admin password')
            ->addArgument('defaultusername', InputArgument::OPTIONAL, 'the default username')
            ->addArgument('defaultpassword', InputArgument::OPTIONAL, 'the default password');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        if(!extension_loaded('memcached')) {
            $output->writeln('<error>extension memcached not found</error>');
            return;
        }
        $helper = $this->getHelper('question');
        if(!$server = $input->getArgument('server')) {
            $question = new Question('Server of the school: ');
            $server = $helper->ask($input, $output, $question);
        }
        if(!$school = $input->getArgument('school')) {
            $question = new Question('school: ');
            $school = $helper->ask($input, $output, $question);
        }
        $admin = [];
        if(!$admin['username'] = $input->getArgument('adminusername')) {
            $question = new Question('Admin username: ');
            $admin['username'] = $helper->ask($input, $output, $question);
        }
        if(!$admin['password'] = $input->getArgument('adminpassword')) {
            $question = new Question('Admin password: ');
            $admin['password'] = $helper->ask($input, $output, $question);
        }
        $user = [];
        if(!$user['username'] = $input->getArgument('defaultusername')) {
            $question = new Question('User username: ');
            $user['username'] = $helper->ask($input, $output, $question);
        }
        if(!$user['password'] = $input->getArgument('defaultpassword')) {
            $question = new Question('User password: ');
            $user['password'] = $helper->ask($input, $output, $question);
        }
        $config = new WebuntisConfiguration([
            'default' => [
                'server' => $server,
                'school' => $school,
                'username' => $user['username'],
                'password' => $user['password']
            ],
            'admin' => [
                'server' => $server,
                'school' => $school,
                'username' => $admin['username'],
                'password' => $admin['password']
            ]
        ]);

        $query = new Query();

        $ymlConfig = new YAMLConfiguration();

        $models = $ymlConfig;

        foreach($models as $key => $value) {
            if($value instanceof CachableModelInterface) {
                $query->get($key)->findAll();
            }
        }
        $output->writeln('<info>successfully built cache!</info>');
    }
}