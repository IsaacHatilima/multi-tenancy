import Layout from '@/Layouts/AuthenticatedLayout';
import CreateTask from '@/Pages/Tasks/Partials/CreateTask';
import { PaginatedTasks, Task } from '@/types/task';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { Badge, Card, Group, Pagination, Table } from '@mantine/core';

export default function Index() {
    const tasks: PaginatedTasks = usePage().props.tasks as PaginatedTasks;

    const rows = tasks?.data.map((task: Task) => (
        <Table.Tr key={task.id}>
            <Table.Td>
                <Link href={route('tasks.show', task.id)}>
                    <span className="text-sky-600">{task.title}</span>
                </Link>
            </Table.Td>
            <Table.Td>
                {task.description.length > 60
                    ? task.description.substring(0, 60) + '...'
                    : task.description}
            </Table.Td>
            <Table.Td>{task.start}</Table.Td>
            <Table.Td>{task.end}</Table.Td>
            <Table.Td>
                {task.assigned_to.profile.first_name}{' '}
                {task.assigned_to.profile.last_name}
            </Table.Td>
            <Table.Td>
                <Badge
                    color={
                        task.priority == 'low'
                            ? 'green'
                            : task.priority == 'medium'
                              ? 'yellow'
                              : 'red'
                    }
                    variant="filled"
                    size="sm"
                >
                    <div className="flex items-center gap-2 p-1">
                        {task.priority.charAt(0).toUpperCase() +
                            task.priority.slice(1)}
                    </div>
                </Badge>
            </Table.Td>
            <Table.Td>
                <Badge
                    color={
                        task.status == 'complete'
                            ? 'green'
                            : task.status == 'pending'
                              ? 'yellow'
                              : task.status == 'in_progress'
                                ? 'blue'
                                : 'red'
                    }
                    variant="filled"
                    size="sm"
                >
                    <div className="flex items-center gap-2 p-1">
                        {task.status
                            .replace('_', ' ')
                            .replace(/\b\w/g, (char) => char.toUpperCase())}
                    </div>
                </Badge>
            </Table.Td>
        </Table.Tr>
    ));
    return (
        <Layout>
            <Head title="Tasks" />

            <Card
                shadow="sm"
                padding="lg"
                radius="md"
                withBorder
                className="mt-2"
            >
                <div className="flex justify-between">
                    <h2 className="mb-4 text-lg font-semibold">Tasks</h2>
                    <CreateTask />
                </div>
                <Table striped highlightOnHover>
                    <Table.Thead>
                        <Table.Tr>
                            <Table.Th>Title</Table.Th>
                            <Table.Th>Description</Table.Th>
                            <Table.Th>Start</Table.Th>
                            <Table.Th>End</Table.Th>
                            <Table.Th>Assigned To</Table.Th>
                            <Table.Th>Priority</Table.Th>
                            <Table.Th>Status</Table.Th>
                        </Table.Tr>
                    </Table.Thead>
                    <Table.Tbody>{rows}</Table.Tbody>
                </Table>

                <div className="mt-4 flex justify-end">
                    <Pagination.Root
                        total={tasks.last_page}
                        value={tasks.current_page}
                        getItemProps={(page) => ({
                            href: tasks.links[page]?.url,
                            onClick: () => {
                                if (tasks.links[page]?.url) {
                                    router.get(tasks.links[page].url);
                                }
                            },
                        })}
                    >
                        <Group gap={5} justify="center">
                            <Pagination.First
                                onClick={() => router.visit(tasks.path)}
                            />
                            <Pagination.Previous
                                onClick={() =>
                                    router.visit(tasks.prev_page_url)
                                }
                            />

                            <Pagination.Items />

                            <Pagination.Next
                                onClick={() =>
                                    router.visit(tasks.next_page_url)
                                }
                            />
                            <Pagination.Last
                                onClick={() =>
                                    router.visit(tasks.last_page_url)
                                }
                            />
                        </Group>
                    </Pagination.Root>
                </div>
                <div className="mt-4 flex justify-end">
                    <p className="text-sm font-thin text-gray-400">
                        {tasks.from} to {tasks.to} of {tasks.total}
                    </p>
                </div>
            </Card>
        </Layout>
    );
}
