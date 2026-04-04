<?php

declare(strict_types=1);

namespace App\Advertise;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use RuntimeException;

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
        $io->text('Введите 3 строки с ключевыми словами (через запятую).');

        $line1 = $io->ask(question: 'Строка 1:', default: null, validator: function (?string $value) {
            if (empty(trim($value ?? ''))) {
                throw new RuntimeException('Строка 1 обязательна для заполнения.');
            }
            return $value;
        });

        $line2 = $io->ask(question: 'Строка 2:', default: null, validator: function (?string $value) {
            if (empty(trim($value ?? ''))) {
                throw new RuntimeException('Строка 2 обязательна для заполнения.');
            }
            return $value;
        });

        $line3 = $io->ask(question: 'Строка 3:', default: null, validator: function (?string $value) {
            if (empty(trim($value ?? ''))) {
                throw new RuntimeException('Строка 3 обязательна для заполнения.');
            }
            return $value;
        });

        $words1 = array_map(
            fn(string $word) => InputSanitazer::sanitaze($word),
            $this->parseLine($line1)
        );
        $words2 = $this->parseLine($line2);
        $words3 = $this->parseLine($line3);

        $io->newLine(2);
        $io->title('Результат парсинга');

        $io->section('Строка 1');
        $io->listing($words1);

        $io->section('Строка 2');
        $io->listing($words2);

        $io->section('Строка 3');
        $io->listing($words3);

        $allWords = array_merge($words1, $words2, $words3);
        $uniqueWords = array_unique(array_map('trim', $allWords));

        $io->section('Все уникальные ключевые слова');
        $io->listing($uniqueWords);

        $io->success('Парсинг успешно завершён!');

        return Command::SUCCESS;
    }

    private function parseLine(string $line): array
    {
        if (empty(trim($line))) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $line)));
    }
}
