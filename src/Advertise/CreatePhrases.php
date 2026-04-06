<?php

declare(strict_types=1);

namespace App\Advertise;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-phrases',
    description: 'Команда генерирует фразы для рекламной кампании'
)]
final class CreatePhrases extends Command
{

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Парсер поискового запроса');
        $io->text([
            'Введите ключевые слова по строкам.',
            'Каждая строка должна содержать слова через запятую.',
            'Для завершения ввода оставьте строку пустой (просто нажмите Enter).'
        ]);
        $io->newLine();

        $allLines = [];

        while (true) {
            $line = $io->ask(
                question: "пустая строка — завершить ввод:",
                default: null,
                validator: function (?string $value) {
                    if ($value === null || trim($value) === '') {
                        return '';
                    }
                    return $value;
                }
            );
            if ($line === null || trim($line) === '') {
                break;
            }

            $allLines[] = $line;
        }

        if (empty($allLines)) {
            $io->error('Не введено ни одной строки!');
            return Command::FAILURE;
        }
        $userInputSanitazer = new UserInputSanitazer();

        $sanitazedLines = $userInputSanitazer->sanitaze($allLines);


        $permutations = PermutationGenerator::generateSearchQueries($sanitazedLines);

        return Command::SUCCESS;
    }
}
