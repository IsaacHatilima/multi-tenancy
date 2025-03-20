import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import TaskLogs from '@/Pages/Tasks/Partials/TaskLogs';
import UpdateTask from '@/Pages/Tasks/Partials/UpdateTask';
import { Task, TaskLog } from '@/types/task';
import { Head, usePage } from '@inertiajs/react';
import { Card } from '@mantine/core';

export default function TaskDetails() {
    const task: Task = usePage().props.task as Task;
    const taskLogs: TaskLog[] = usePage().props.taskLogs as TaskLog[];
    return (
        <AuthenticatedLayout>
            <Head title="TaskDetails" />

            <div className="mb-10 flex items-center justify-center">
                <UpdateTask task={task} />
            </div>

            <div className="flex items-center justify-center">
                <Card
                    shadow="sm"
                    padding="lg"
                    radius="md"
                    withBorder
                    className="flex w-1/2 items-center justify-center"
                >
                    <TaskLogs taskLogs={taskLogs} />
                </Card>
            </div>
        </AuthenticatedLayout>
    );
}
