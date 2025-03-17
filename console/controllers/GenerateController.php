<?php

namespace console\controllers;

use common\models\Examination;
use common\models\Patient;
use common\models\User;
use DateTime;
use Exception;
use Throwable;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\console\widgets\Table;
use yii\db\ActiveRecord;

class GenerateController extends Controller
{
    public string $count = '5';
    public string $endId = '0';
    public string $startId = '0';
    public string $endHour = '19';
    public string $startHour = '8';
    public string $endDate = '2023-10-31';
    public string $startDate = '2023-07-15';
    public string $dryRun = 'false';
    public string $excludeSundays = 'true';

    /**
     * @inheritDoc
     */
    public function options($actionID): array
    {
        return ['count', 'startDate', 'endDate', 'startHour', 'endHour', 'excludeSundays', 'dryRun', 'startId', 'endId'];
    }

    /**
     * @inheritDoc
     */
    public function optionAliases(): array
    {
        return [
            'cnt' => 'count',
        ];
    }

    /**
     * An action for `generate/examinations` command-line command. Generates given number of examinations for given date
     * span and time span.
     *
     * Generates `--count` number of examinations for dates between `--startDate` and --endDate, excluding Sundays or not
     * (`--excludeSundays`) and for hours between `--startHour` and `--endHour`.
     *
     *  Note that start and end dates are kept separately from start and end time. This is because dates usually denotes
     *  long period of time while times denotes "working hours" of given day.
     *
     * All parameters has default values (see beginning of this file) so it is possible to call just `generate/examinations`
     * command alone, without any parameters. This will generate 5 examinations for dates between 2023-07-15 and 2023-10-31,
     * starting 08:00:00 and ending 19:59:59 and excluding Sundays.
     *
     * Setting `--dryRun` to `true` will execute entire script without actual modifications to the database.
     *
     * @return int exit code
     *
     * @throws Exception|Throwable on error creating date
     */
    public function actionExaminations(): int
    {
        $this->endDate = date('Y-m-d', (strtotime($this->endDate) ?: strtotime('2023-07-15')));
        $this->startDate = date('Y-m-d', (strtotime($this->startDate) ?: strtotime('2023-10-31')));

        $count = (int)$this->count;
        $endHour = (int)$this->endHour;
        $startHour = (int)$this->startHour;
        $endDate = strtotime($this->endDate);
        $startDate = strtotime($this->startDate);
        $dryRun = ($this->dryRun === 'yes' || $this->dryRun === 'true' || $this->dryRun === '1');
        $excludeSundays = ($this->excludeSundays === 'yes' || $this->excludeSundays === 'true' || $this->excludeSundays === '1');

        $this->checkBasicInputParameters($count, $endHour, $startHour);
        if ($count >= 100) $this->error("The value of the -cnt (--count) parameter [$count] cannot be greater than 100.");
        if ($endDate < $startDate) $this->error("The value of the --endDate parameter [$endDate] must be greater than the value of the --startDate parameter [$startDate].");

        echo 'Generate ' . $count . ' examination' . ($count === 1 ? '' : 's');
        echo ' for dates between ' . $this->startDate . ' and ' . $this->endDate . ',';
        echo ' for hours starting ' . $this->startHour . ' and ending ' . $this->endHour;
        echo $excludeSundays ? ' and excluding Sundays.' : '. Sundays won\'t be excluded.';
        echo $dryRun ? "\n" . 'The --dryRun option is enabled. Skipping confirmation. No actual changes to the database will be performed.' : '';
        echo "\n";

        if (!$dryRun && !$this->confirm("Continue?")) return ExitCode::OK;

        $rows = [];

        for ($i = 1; $i <= $count; $i++) {
            $user = $this->getRandomModel();
            $patient = $this->getRandomModel(true);
            $createDate = $this->getRandomDate($startDate, $endDate, $startHour, $endHour, $excludeSundays);
            $updateDate = $this->getRandomDate($createDate->getTimestamp(), $endDate, $startHour, $endHour, $excludeSundays);

            if (!$dryRun) {
                $result = Yii::$app->db->createCommand()->insert('{{%examination}}', [
                    'created_by' => $user->id,
                    'patient_id' => $patient->id,
                    'created_at' => $createDate->getTimestamp(),
                    'updated_at' => $updateDate->getTimestamp()
                ])->execute();
            }

            $rows[] = [
                $i,
                $user->getFullName(),
                $patient->getFullName(),
                $createDate->format('Y-m-d H:i:s'),
                $updateDate->format('Y-m-d H:i:s'),
                $dryRun ? 'DR' : ($result === 1 ? 'OK' : 'ER')
            ];
        }

        echo Table::widget([
            'headers' => ['#', 'Operator', 'Patient', 'Created', 'Updated', '??'],
            'rows' => $rows,
        ]);

        return ExitCode::OK;
    }

    /**
     * An action for `generate/results` command-line command. Generates given number of results for given examination
     *  IDs span span.
     *
     * Iterates entire examinations table starting with `--startId` and ending with `--endId`. Skips all non-existing examinations.
     * Generates a random number of 1 up to `--count` number of results for each valid examination ID, for dates between
     * examination's creation date and examination's update date, excluding Sundays or not (`--excludeSundays`) and for hours
     * between `--startHour` and `--endHour`.
     *
     *  Note that start and end dates are kept separately from start and end time. This is because dates usually denotes
     *  long period of time while times denotes "working hours" of given day.
     *
     * Most parameters has default values (see beginning of this file) and will generate 5 results for dates taken from
     * corresponding examination (excluding Sundays) and for hours between 08:00:00 and ending 19:59:59. However, both
     * `startId` and `endId` must be provided in all scenarios, and it is not possible to run this command without them.
     *
     * Setting `--dryRun` to `true` will execute entire script without actual modifications to the database.
     *
     * Note that this method is using a simple implementation of "skip Sundays" functionality which may potentially run
     * into very long date generation or even into endless loop. But, since this command is ought to be executed 2-3 per
     * year at this risk is acceptable in favor of not implementing overcomplicated implementation of "skip Sundays" instead.
     *
     * @return int exit code
     *
     * @throws Exception|Throwable on error creating date
     */
    public function actionResults(): int
    {
        $count = (int)$this->count;
        $endId = (int)$this->endId;
        $startId = (int)$this->startId;
        $endHour = (int)$this->endHour;
        $startHour = (int)$this->startHour;
        $dryRun = ($this->dryRun === 'yes' || $this->dryRun === 'true' || $this->dryRun === '1');
        $excludeSundays = ($this->excludeSundays === 'yes' || $this->excludeSundays === 'true' || $this->excludeSundays === '1');

        $examinationCount = Examination::find()->max('id');

        $this->checkBasicInputParameters($count, $endHour, $startHour);
        if ($endId < 1) $this->error("The value of the --endId parameter [$endId] must be greater than zero.");
        if ($startId < 1) $this->error("The value of the --startId parameter [$startId] must be greater than zero.");
        if ($count >= 10)  $this->error("The value of the -cnt (--count) parameter [$count] cannot be greater than 10.");
        if ($endId > $examinationCount) $this->error("The value of the --endId parameter [$endId] must not be greater than $examinationCount.");
        if ($startId > $examinationCount) $this->error("The value of the --startId parameter [$startId] must not be greater than $examinationCount.");
        if ($endId < $startId) $this->error("The value of the --endId parameter [$endId] must be greater than the value of the --startId parameter [$startId].");

        $isFullSet = ($startId === 1 && $endId === $examinationCount);

        echo $startId === $endId ? 'Modify examination with ID = ' . $startId . '. ' : 'Iterate' . ($isFullSet ? ' all' : '') . ' examinations' . ($isFullSet ? '' : ' between ID = ' . $startId . ' and ID = ' . $endId) . '. ';
        echo 'Generate ' . ($count === 1 ? '1 result' : 'random number of results between 1 and ' . $count) . ($startId === $endId ? '' : ' for each found examination');
        echo ', for dates between examination\'s creation date and update date';
        echo $excludeSundays ? ' (excluding Sundays),' : ',';
        echo ' and for hours starting ' . $this->startHour . ' and ending ' . $this->endHour . '.';
        echo $dryRun ? "\n" . 'The --dryRun option is enabled. Skipping confirmation. No actual changes to the database will be performed.' : '';
        echo "\n";

        if (!$dryRun && !$this->confirm("Continue?")) return ExitCode::OK;

        $rows = [];

        for ($i = $startId; $i <= $endId; $i++) {
            $examination = Examination::find()->where(['id' => $i])->one();

            if (is_null($examination)) continue;

            $resultsCount = rand(1, $count);

            for ($j = 1; $j <= $resultsCount; $j++) {
                $user = $this->getRandomModel();
                $createDate = $this->getRandomDate($examination->created_at, $examination->updated_at, $startHour, $endHour, $excludeSundays);
                $updateDate = $this->getRandomDate($createDate->getTimestamp(), $examination->updated_at, $startHour, $endHour, $excludeSundays);

                $mainComment = '';
                $hipLeft = $this->getRandomFloatNumber(2.42, 6.09);
                $hipRight = $this->getRandomFloatNumber(2.42, 6.09);
                $hipDifference = round(abs($hipLeft - $hipRight), 2);
                $legLeft = $this->getRandomFloatNumber(71.52, 75.42);
                $legRight = $this->getRandomFloatNumber(71.52, 75.42);
                $legDifference = round(abs($legLeft - $legRight), 2);
                $shoulderLeft = $this->getRandomFloatNumber(50.64, 53.49);
                $shoulderRight = $this->getRandomFloatNumber(50.64, 53.49);
                $shoulderDifference = round(abs($shoulderLeft - $shoulderRight), 2);
                $distanceFeet = $this->getRandomFloatNumber(12.65, 22.14);
                $distanceKnees = $this->getRandomFloatNumber(11.31, 20.11);
                $suspicionScoliosis = rand(0, 1);
                $suspicionKneeVarus = rand(0, 1);
                $suspicionKneeValgus = rand(0, 1);

                if (!$dryRun) {
                    $result = Yii::$app->db->createCommand()->insert('{{%result}}', [
                        'main_comment' => $mainComment,
                        'hip_left' => $hipLeft,
                        'hip_right' => $hipRight,
                        'hip_difference' => $hipDifference,
                        'leg_left' => $legLeft,
                        'leg_right' => $legRight,
                        'leg_difference' => $legDifference,
                        'shoulder_left' => $shoulderLeft,
                        'shoulder_right' => $shoulderRight,
                        'shoulder_difference' => $shoulderDifference,
                        'distance_feet' => $distanceFeet,
                        'distance_knees' => $distanceKnees,
                        'suspicion_scoliosis' => $suspicionScoliosis,
                        'suspicion_knee_varus' => $suspicionKneeVarus,
                        'suspicion_knee_valgus' => $suspicionKneeValgus,
                        'created_by' => $user->id,
                        'created_at' => $createDate->getTimestamp(),
                        'updated_at' => $updateDate->getTimestamp(),
                        'examination_id' => $i,
                    ])->execute();
                }

                $rows[] = [
                    $i,
                    $j,
                    $createDate->format('Y-m-d H:i:s'),
                    $updateDate->format('Y-m-d H:i:s'),
                    $user->getFullName(),
                    $hipLeft,
                    $hipRight,
                    $hipDifference,
                    $legLeft,
                    $legRight,
                    $legDifference,
                    $shoulderLeft,
                    $shoulderRight,
                    $shoulderDifference,
                    $distanceFeet,
                    $distanceKnees,
                    $suspicionScoliosis ? 'Yes' : 'No',
                    $suspicionKneeVarus ? 'Yes' : 'No',
                    $suspicionKneeValgus ? 'Yes' : 'No',
                    $dryRun ? 'DR' : ($result === 1 ? 'OK' : 'ER')
                ];
            }
        }

        echo Table::widget([
            'headers' => [
                'ID', '#', 'Created', 'Updated', 'Operator',
                'hipL', 'hipR', 'hipD',
                'legL', 'legR', 'legD',
                'shlL', 'shlR', 'shlD',
                'dFeet', 'dKnee',
                'sSco', 'sVar', 'sVal',
                '??'
            ],
            'rows' => $rows,
        ]);

        return ExitCode::OK;
    }

    /**
     * Validates basic input parameters (i.e. those that are the same for both "generate/examinations" and "generate/results"
     * commands. Displays corresponding error message and breaks script execution (thus void as a returns value).
     *
     * @param int $startHour first possible hour when examination or result can be generated
     * @param int $endHour last possible hour when examination or result can be generated
     * @param int $count number of examinations or results to be generated
     * @return void
     */
    private function checkBasicInputParameters(int $count, int $endHour, int $startHour): void
    {
        if ($endHour < 0) $this->error("The value of the --endHour parameter [$endHour] must be greater than zero.");
        if ($endHour > 23) $this->error("The value of the --endHour parameter [$endHour] must not be greater than 23.");
        if ($startHour < 0) $this->error("The value of the --startHour parameter [$startHour] must be greater than zero.");
        if ($startHour > 23) $this->error("The value of the --startHour parameter [$startHour] must not be greater than 23.");
        if ($endHour < $startHour) $this->error("The value of the --endHour parameter [$endHour] must be greater than the value of the --startHour parameter [$startHour].");
        if ($count <= 0) $this->error("The value of the -cnt (--count) parameter [$count] must be greater than zero.");

        $this->endHour = ($endHour > 9 ? $endHour : '0' . $endHour) . ':59:59';
        $this->startHour = ($startHour > 9 ? $startHour : '0' . $startHour) . ':00:00';
    }

    /**
     * A shorthand function to display some error message and terminate current script execution.
     *
     * @param string $errorText message to be displayed
     */
    private function error(string $errorText): void
    {
        echo $errorText."\n";

        exit;
    }

    /**
     * Generate a random date in range between $startDate and $endDate, excluding Sundays, if $excludeSundays is set to true.
     * Sets time part of returned DateTime object to an random hour, minute and second in range between $startHour and $endHour.
     *
     * Note that this method uses a simple implementation of "skip Sundays" functionality which may potentially run into
     * very long date generation or even into endless loop. But, since this command is ought to be executed 2-3 per year
     * and this risk is acceptable in favor of not implementing overcomplicated implementation of "skip Sundays" instead.
     * See below Stack Overflow question for details.
     *
     * @param int $startDate
     * @param int $endDate
     * @param int $startHour
     * @param int $endHour
     * @param bool $excludeSundays
     *
     * @return DateTime generated random date
     *
     * @throws Exception|Throwable on error creating date
     *
     * @see https://stackoverflow.com/a/77513232/1469208/
     */
    private function getRandomDate(int $startDate, int $endDate, int $startHour, int $endHour, bool $excludeSundays): DateTime
    {
        $theDate = new DateTime();

        do {
            $theDate
                ->setTimestamp(rand($startDate, $endDate))
                ->setTime(rand($startHour, $endHour), rand(0, 59), rand(0, 59));
        } while ($excludeSundays && $theDate->format('N') == 7);

        return $theDate;
    }

    /**
     * Returns a random user's or patient's model.
     *
     * @param bool $patient whether to return Patient or User model
     *
     * @return ActiveRecord generated random user's or patient's model
     */
    private function getRandomModel(bool $patient = false): ActiveRecord
    {
        $models = $patient ? Patient::find()->all() : User::find()->all();

        return $models[rand(1, count($models) - 1)];
    }

    /**
     * Generates a float random number in $min..$max range and rounds it up to $precision number of decimal digits.
     *
     * @param float $min minimum value of a random number
     * @param float $max maximum value of a random number
     * @param int $precision number of digits after decimal point
     *
     * @return float generated random number
     */
    private function getRandomFloatNumber (float $min, float $max, int $precision = 2): float {
        $randValue = $min + lcg_value() * (abs($max - $min));

        return round($randValue, $precision);
    }
}