<?php

declare(strict_types=1);

namespace App\Advertise;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Advertise\KeyPhraseSanitazer;

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
                    // Пропускаем валидацию для пустой строки (завершение ввода)
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

        var_dump($sanitazedLines);

        $permutations = PermutationGenerator::generateSearchQueries($sanitazedLines);


        return Command::SUCCESS;

        // $permutations = PermutationGenerator::generate($preparedUserInput);
        //
        // $io->newLine(2);
        // $io->title('Результат парсинга');
        //
        // $io->section('Строка 1');
        // $io->listing($words1);
        //
        // $io->section('Строка 2');
        // $io->listing($words2);
        //
        // $io->section('Строка 3');
        // $io->listing($words3);
        //
        // $allWords = array_merge($words1, $words2, $words3);
        // $uniqueWords = array_unique(array_map('trim', $allWords));
        //
        // $io->section('Все уникальные ключевые слова');
        // $io->listing($uniqueWords);
        //
        // $io->success('Парсинг успешно завершён!');
        //
        // $io->section('Перестановки');
        //
        // return Command::SUCCESS;
    }

    private function parseLine(string $line): array
    {
        if (empty(trim($line))) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $line)));
    }
}
