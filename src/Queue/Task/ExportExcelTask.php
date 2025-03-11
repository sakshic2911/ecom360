<?php

namespace App\Queue\Task;

use Queue\Queue\Task;

class ExportExcelTask extends Task {

	/**
	 * @param array<string, mixed> $data The array passed to QueuedJobsTable::createJob()
	 * @param int $jobId The id of the QueuedJob entity
	 * @return void
	 */
	public function run(array $data, int $jobId): void {
	}

}