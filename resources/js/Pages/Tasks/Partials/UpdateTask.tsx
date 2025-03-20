import { Task } from '@/types/task';
import { User } from '@/types/user';
import { useForm, usePage } from '@inertiajs/react';
import { Button, Card, Select, Textarea, TextInput } from '@mantine/core';
import { DateInput } from '@mantine/dates';
import { useDisclosure } from '@mantine/hooks';
import { notifications } from '@mantine/notifications';
import dayjs from 'dayjs';
import { FormEventHandler } from 'react';

function UpdateTask({ task }: { task: Task }) {
    const users = usePage().props.users as User[];
    const [loading, { open, close }] = useDisclosure();

    const { data, setData, errors, put } = useForm({
        assigned_to: String(task.assigned_to.id),
        priority: task.priority,
        escalation: task.escalation,
        status: task.status,
        title: task.title,
        description: task.description,
        start: dayjs(task.start).format('YYYY-MM-DD'),
        end: dayjs(task.end).format('YYYY-MM-DD'),
    });

    const handleSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        open();
        put(route('tasks.update', task.id), {
            onSuccess: () => {
                notifications.show({
                    title: 'Success',
                    message: 'Task has been updated successfully!',
                    color: 'green',
                });
            },
            onFinish: () => {
                close();
            },
            onError: () => {},
        });
    };

    return (
        <Card
            shadow="sm"
            padding="lg"
            radius="md"
            withBorder
            className="flex w-1/2 items-center justify-center"
        >
            <form onSubmit={handleSubmit} className="mt-6 w-full">
                <div>
                    <div className="">
                        <TextInput
                            className="w-full"
                            id="title"
                            name="title"
                            value={data.title}
                            error={errors.title}
                            withAsterisk
                            autoComplete="title"
                            mt="md"
                            autoFocus
                            label="Title"
                            onChange={(e) => setData('title', e.target.value)}
                            inputWrapperOrder={[
                                'label',
                                'input',
                                'description',
                                'error',
                            ]}
                        />
                    </div>
                    <div className="mt-3">
                        <Textarea
                            className="w-full"
                            id="description"
                            name="description"
                            value={data.description}
                            error={errors.description}
                            withAsterisk
                            autosize={true}
                            minRows={5}
                            autoComplete="description"
                            mt="md"
                            autoFocus
                            label="Description"
                            onChange={(e) =>
                                setData('description', e.target.value)
                            }
                            inputWrapperOrder={[
                                'label',
                                'input',
                                'description',
                                'error',
                            ]}
                        />
                    </div>
                    <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <Select
                            mt="md"
                            label="Assigned To"
                            error={errors.assigned_to}
                            value={data.assigned_to}
                            withAsterisk
                            data={[
                                { label: 'Select User', value: '' },
                                ...users.map((user) => ({
                                    label: `${user.profile.first_name} ${user.profile.last_name}`,
                                    value: String(user.id),
                                })),
                            ]}
                            onChange={(_value, option) => {
                                setData('assigned_to', option.value);
                            }}
                            inputWrapperOrder={[
                                'label',
                                'input',
                                'description',
                                'error',
                            ]}
                        />
                        <Select
                            mt="md"
                            label="Priority"
                            error={errors.priority}
                            value={data.priority}
                            withAsterisk
                            data={[
                                { label: 'Select Priority', value: '' },
                                { label: 'Low', value: 'low' },
                                { label: 'Medium', value: 'medium' },
                                { label: 'High', value: 'high' },
                            ]}
                            onChange={(_value, option) => {
                                setData('priority', option.value);
                            }}
                            inputWrapperOrder={[
                                'label',
                                'input',
                                'description',
                                'error',
                            ]}
                        />
                    </div>
                    <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <Select
                            mt="md"
                            label="Escalation"
                            error={errors.escalation}
                            value={data.escalation}
                            withAsterisk
                            data={[
                                { label: 'Select Escalation', value: '' },
                                { label: 'Level 1', value: 'level1' },
                                { label: 'Level 2', value: 'level2' },
                                { label: 'Level 3', value: 'level3' },
                            ]}
                            onChange={(_value, option) => {
                                setData('escalation', option.value);
                            }}
                            inputWrapperOrder={[
                                'label',
                                'input',
                                'description',
                                'error',
                            ]}
                        />
                        <Select
                            mt="md"
                            label="Status"
                            error={errors.status}
                            value={data.status}
                            withAsterisk
                            data={[
                                { label: 'Select Status', value: '' },
                                { label: 'Pending', value: 'pending' },
                                {
                                    label: 'In Progress',
                                    value: 'in_progress',
                                },
                                { label: 'Complete', value: 'complete' },
                                { label: 'Cancelled', value: 'cancelled' },
                            ]}
                            onChange={(_value, option) => {
                                setData('status', option.value);
                            }}
                            inputWrapperOrder={[
                                'label',
                                'input',
                                'description',
                                'error',
                            ]}
                        />
                    </div>
                    <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <DateInput
                            mt="md"
                            label="Start"
                            placeholder="Start"
                            withAsterisk
                            error={errors.start}
                            value={new Date(data.start)}
                            onChange={(date) => {
                                const formattedDate =
                                    dayjs(date).format('YYYY-MM-DD');
                                setData('start', formattedDate);
                            }}
                            valueFormat="YYYY-MM-DD"
                        />
                        <DateInput
                            mt="md"
                            label="End"
                            placeholder="End"
                            withAsterisk
                            error={errors.end}
                            value={new Date(data.end)}
                            onChange={(date) => {
                                const formattedDate =
                                    dayjs(date).format('YYYY-MM-DD');
                                setData('end', formattedDate);
                            }}
                            valueFormat="YYYY-MM-DD"
                        />
                    </div>
                </div>

                <div className="mt-4 flex justify-end gap-2">
                    <Button
                        type="submit"
                        variant="filled"
                        color="black"
                        loading={loading}
                        loaderProps={{ type: 'dots' }}
                    >
                        Update
                    </Button>
                </div>
            </form>
        </Card>
    );
}

export default UpdateTask;
