<?php

if ($argc < 3) {
    fwrite(STDERR, "Usage: php scripts/sqlite-to-mysql-dump.php <sqlite-path> <output-sql>\n");
    exit(1);
}

[$script, $sqlitePath, $outputPath] = $argv;

if (! is_file($sqlitePath)) {
    fwrite(STDERR, "SQLite database not found: {$sqlitePath}\n");
    exit(1);
}

$tables = [
    'users',
    'pemilik',
    'kapal',
    'karyawan',
    'kendaraan',
    'transaksi_operasional',
    'pengeluaran',
    'gaji_telly',
    'paguyuban',
    'operasional_rekap',
    'activity_logs',
];

$sqlite = new PDO('sqlite:'.$sqlitePath);
$sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$out = fopen($outputPath, 'wb');

if (! $out) {
    fwrite(STDERR, "Cannot write output: {$outputPath}\n");
    exit(1);
}

fwrite($out, "-- Data-only dump generated from {$sqlitePath}\n");
fwrite($out, "-- Import into an already-migrated Laravel MySQL database.\n\n");
fwrite($out, "SET FOREIGN_KEY_CHECKS=0;\n");

foreach (array_reverse($tables) as $table) {
    fwrite($out, "TRUNCATE TABLE `{$table}`;\n");
}

fwrite($out, "\n");

foreach ($tables as $table) {
    $exists = $sqlite
        ->prepare("SELECT name FROM sqlite_master WHERE type = 'table' AND name = :table");
    $exists->execute(['table' => $table]);

    if (! $exists->fetchColumn()) {
        continue;
    }

    $rows = $sqlite->query('SELECT * FROM "'.$table.'"')->fetchAll(PDO::FETCH_ASSOC);

    if ($rows === []) {
        continue;
    }

    $columns = array_keys($rows[0]);
    $columnList = implode(', ', array_map(fn ($column) => '`'.$column.'`', $columns));

    fwrite($out, "-- {$table}\n");

    foreach ($rows as $row) {
        $values = array_map(function ($value) use ($sqlite) {
            if ($value === null) {
                return 'NULL';
            }

            return $sqlite->quote((string) $value);
        }, array_values($row));

        fwrite($out, "INSERT INTO `{$table}` ({$columnList}) VALUES (".implode(', ', $values).");\n");
    }

    fwrite($out, "\n");
}

fwrite($out, "SET FOREIGN_KEY_CHECKS=1;\n");
fclose($out);

echo "Dump written to {$outputPath}\n";
