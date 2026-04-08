<?php
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
$files = ['dtr-jan-2026.xlsx','DTR-Monthly-Template.xlsx'];
foreach ($files as $f) {
  if (!file_exists($f)) continue;
  echo "\n=== $f ===\n";
  $s = IOFactory::load($f)->getActiveSheet();
  for ($r=1;$r<=60;$r++) {
    $vals=[];
    foreach (range('A','J') as $c) {
      $v=$s->getCell($c.$r)->getCalculatedValue();
      if ($v!==null && $v!=='') $vals[]=$c.$r.'='.trim((string)$v);
    }
    if (!empty($vals)) echo implode(' | ',$vals)."\n";
  }
}
