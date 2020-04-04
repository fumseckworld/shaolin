<?php

namespace Eywa\Versioning {

    use Eywa\Collection\Collect;
    use Eywa\Console\Shell;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Question\Question;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class Version
    {
        private SymfonyStyle $io;

        private string $commit_success;
        private string $commit_fail;
        private string $commit_message_prompt;
        private array $commits_completions;
        private string $run_test_message;
        private string $tests_success_message;
        private string $confirm_send_app_message;
        private bool $confirm_send_default_value;
        private string $send_application_message;
        private string $remote_update_success_message;
        private string $remote_update_fail_message;
        private string $send_application_message_to_remote;
        private string $remotes_updated_success;
        private string $end_of_remotes_message;
        private string $no_remotes_found;
        private string $confirm_add_message;
        private string $add_fail_message;
        private string $add_success_message;
        private string $type_the_filename_message;
        private string $no_files_message;
        private string $continue_message;
        private bool $continue_default_value;
        private bool $checked = false;
        private Collect $errors;

        /**
         *
         * Version constructor.
         *
         * @param SymfonyStyle $io
         *
         * @throws Kedavra
         *
         */
        public function __construct(SymfonyStyle $io)
        {
            if (!$this->checked) {
                if (!is_dir(base('.git'))) {
                    throw new Kedavra('The project is not a git repository');
                }

                $this->errors = collect();
                $this->io = $io;

                $file = 'versioning';

                $this->commit_success = strval(config($file, 'commit-success-message'));
                $this->commit_message_prompt = strval(config($file, 'commit-message-prompt'));
                $this->commit_fail = strval(config($file, 'commit-fail-message'));
                $this->run_test_message = strval(config($file, 'start-tests-message'));
                $this->tests_success_message = strval(config($file, 'tests-success-message'));
                $this->confirm_send_app_message = strval(config($file, 'confirm-send-message'));
                $this->confirm_send_default_value = config($file, 'confirm-send-message-default-value') == true;
                $this->send_application_message = strval(config($file, 'send-message'));
                $this->send_application_message_to_remote = strval(config($file, 'send-message-to'));
                $this->remote_update_success_message = strval(config($file, 'remote-updated-success-message'));
                $this->remote_update_fail_message = strval(config($file, 'remote-updated-fail-message'));
                $this->remotes_updated_success = strval(config($file, 'all-remotes-updated-success-message'));
                $this->end_of_remotes_message = strval(config($file, 'end-of-remotes-message'));
                $this->no_remotes_found = strval(config($file, 'remotes-empty'));
                $this->confirm_add_message = strval(config($file, 'confirm-add-message'));
                $this->add_success_message = strval(config($file, 'add-success-message'));
                $this->add_fail_message = strval(config($file, 'add-fail-message'));
                $this->type_the_filename_message = strval(config($file, 'type-filename-prompt'));
                $this->no_files_message = strval(config($file, 'no-files-found-message'));
                $this->continue_message = strval(config($file, 'continue-message'));
                $this->continue_default_value = config($file, 'continue-message-default-value') == true;
                $this->commits_completions = array_merge([
                    'build:',
                    'ci:',
                    'core:',
                    'docs:',
                    'feat:',
                    'fix:',
                    'perf:',
                    'refactor:',
                    'revert:',
                    'design:',
                    'test:'
                ], config($file, 'completions'));
                $this->checked = true;
            }
        }

        /**
         *
         * Commit the changes
         *
         * @return Version
         *
         * @throws Kedavra
         *
         */
        public function commit(): Version
        {
            if ($this->errors->empty()) {
                do {
                    $commit =  $this->io->askQuestion(
                        (new Question(
                            $this->commit_message_prompt,
                            ''
                        ))->setAutocompleterValues($this->commits_completions)
                    );
                } while (not_def($commit) || sum($commit) < 0 || sum($commit) > 60);

                if ((new Shell(sprintf("git commit -m '%s'", $commit)))->run()) {
                    $this->io->success($this->commit_success);
                } else {
                    $this->io->error($this->commit_fail);
                    $this->errors->push($this->commit_fail);
                }
            }
            return $this;
        }

        /**
         *
         * Run all tests
         *
         * @return Version
         *
         */
        public function check(): Version
        {
            $this->io->success($this->run_test_message);
            $x = new Shell(base('vendor', 'bin', 'grumphp') . ' run');
            if ($x->run()) {
                $this->io->success($this->tests_success_message);
            } else {
                $this->io->error($x->get()->getOutput());
                $this->errors->push($x->get()->getOutput());
            }

            return $this;
        }


        /**
         *
         * Get all versioning files
         *
         * @return array
         *
         */
        public function files(): array
        {
            $files = [];
            exec('git ls-files', $files);

            if (not_def($files)) {
                $this->io->error($this->no_files_message);
                $this->errors->push($this->no_files_message);
                return [];
            }
            return $files;
        }

        public function blame(): Version
        {
            if ($this->errors->empty()) {
                do {
                    $file =   $this->io->askQuestion(
                        (new Question(
                            $this->type_the_filename_message
                        ))
                            ->setAutocompleterValues($this->files())
                    );
                    if (file_exists($file)) {
                        $this->io->warning(strval(shell_exec(sprintf('git blame %s', strval($file)))));
                    }
                } while ($this->io->confirm($this->continue_message, $this->continue_default_value));
            }

            return $this;
        }

        /**
         *
         * Show logs
         *
         * @param int $months
         *
         * @return int
         *
         */
        public function logs(int $months): int
        {
            $format = '%h %an %cr %s';
            $this->io->warning(
                strval(
                    shell_exec(
                        sprintf(
                            'git log --pretty=format:"%s" --graph  --since=%d.months',
                            $format,
                            $months
                        )
                    )
                )
            );
            return 0;
        }
        /**
         *
         * Send the application
         *
         * @return Version
         *
         */
        public function send(): Version
        {
            if ($this->errors->empty()) {
                if ($this->io->confirm($this->confirm_send_app_message, $this->confirm_send_default_value)) {
                    $this->io->success($this->send_application_message);
                    foreach ($this->remotes() as $name => $url) {
                        $this->io->warning(sprintf($this->send_application_message_to_remote, $url));

                        if ((new Shell(sprintf('git push %s --all && git push %s --tags', $name, $name)))->run()) {
                            $this->io->success(sprintf($this->remote_update_success_message, $url));
                        } else {
                            $this->io->error(
                                $this->remote_update_fail_message
                            );
                            $this->errors->push($this->remote_update_fail_message);
                        }
                    }
                    $this->io->warning($this->end_of_remotes_message);
                    $this->io->success($this->remotes_updated_success);
                }
            }
            return $this;
        }

        public function success(): int
        {
            if (not_def($this->errors->all())) {
                return 0;
            }
            return 1;
        }

        /**
         *
         * Get the remote
         *
         * @return array
         *
         */
        public function remotes(): array
        {
            $remotes  = [];

            exec('git remote -v', $remotes);

            if (not_def($remotes)) {
                $this->io->error($this->no_remotes_found);
                $this->errors->push($this->no_remotes_found);
                return [];
            }
            $all = collect();

            foreach ($remotes as $remote) {
                $x = collect(explode("\t", $remote));
                $name = $x->first();
                $url = collect(explode(' ', $x->get(1)))->first();

                $host = collect(explode(':', collect(explode('@', $url))->last()))->first();

                if ($all->hasNot($name)) {
                    $all->put($name, $host);
                }
            }
            return $all->all();
        }

        /**
         *
         * Run git add
         *
         * @return Version
         *
         */
        public function add(): Version
        {
            if ($this->errors->empty()) {
                if ($this->io->confirm($this->confirm_add_message, true)) {
                    if ((new Shell('git add .'))->run()) {
                        $this->io->success($this->add_success_message);
                    } else {
                        $this->io->error($this->add_fail_message);
                        $this->errors->push($this->add_fail_message);
                    }
                }
            }

            return $this;
        }
        /**
         *
         * Show the git diff
         *
         * @return Version
         *
         */
        public function diff(): Version
        {
            if ($this->errors->empty()) {
                $this->io->warning(
                    html_entity_decode(
                        sprintf(
                            "%s\n%s",
                            strval(
                                str_replace(
                                    "\t",
                                    '',
                                    strval(
                                        shell_exec(
                                            'git status'
                                        )
                                    )
                                )
                            ),
                            strval(
                                shell_exec('git diff')
                            )
                        ),
                        ENT_QUOTES,
                        'UTF-8'
                    )
                );
            }

            return $this;
        }
    }
}
