<?php
	
	namespace Imperium\Command;
	
	use Imperium\Directory\Dir;
	use Imperium\Exception\Kedavra;
	use Imperium\File\File;
	use Symfony\Component\Console\Command\Command;
	use Symfony\Component\Console\Input\InputArgument;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	
	class GenerateView extends Command
	{
		
		protected static $defaultName = "make:view";
		
		protected function configure()
		{
			
			$this->setDescription('Generate a view')->addArgument('view', InputArgument::REQUIRED, 'the view name')->addArgument('dir', InputArgument::OPTIONAL, 'the view dir');
		}
		
		/**
		 * @param  InputInterface   $input
		 * @param  OutputInterface  $output
		 *
		 * @throws Kedavra
		 * @return int|void|null
		 */
		public function execute(InputInterface $input, OutputInterface $output)
		{
			
			$dir = $input->getArgument('dir');
			
			$view = collect(explode('.', $input->getArgument('view')))->first();
			
			append($view, '.twig');
			
			$path = base('app') . DIRECTORY_SEPARATOR . 'Views';
			
			if(def($dir))
			{
				Dir::checkout($path);
				
				if( ! Dir::exist($dir))
					Dir::create($dir);
				
				Dir::checkout($dir);
			}
			else
			{
				Dir::checkout($path);
			}
			
			if( ! file_exists($view))
			{
				if((new File($view, EMPTY_AND_WRITE_FILE_MODE))->write("{% extends 'layout.twig' %}\n\n{% block title '' %}\n\n{% block description '' %}\n\n{% block css %}\n\n{% endblock %}\n\n{% block content %}\n\n\n\n{% endblock %}\n\n{% block js %}\n\n\n\n{% endblock %}\n")->flush())
					$output->writeln('<info>The view has been generated successfully</info>');
				
				return 0;
			}
			else
			{
				$output->writeln('<bg=red;fg=white>The view already exist </>');
				
				return 1;
			}
		}
		
	}