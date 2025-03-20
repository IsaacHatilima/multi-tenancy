import { TaskLog } from '@/types/task';
import { Text, Timeline } from '@mantine/core';
import { IconArrowRampRight3, IconPlayerPlayFilled } from '@tabler/icons-react';
import dayjs from 'dayjs';

export default function TaskLogs({ taskLogs }: { taskLogs: TaskLog[] }) {
    const formatDate = (dateString: string) => {
        const date = dayjs(dateString);
        const now = dayjs();
        const diffInHours = now.diff(date, 'hour');
        const diffInDays = now.diff(date, 'day');

        if (diffInHours < 24) {
            return `${diffInHours} ${diffInHours === 1 ? 'hour' : 'hours'} ago at ${date.format('HH:mm:ss')}`;
        } else if (diffInDays < 7) {
            return `${diffInDays} ${diffInDays === 1 ? 'day' : 'days'} ago on ${date.format('YYYY-MM-DD HH:mm:ss')}`;
        } else {
            return date.format('YYYY-MM-DD HH:mm:ss');
        }
    };

    return (
        <Timeline
            active={taskLogs?.length - 0}
            bulletSize={24}
            lineWidth={2}
            className="w-2/3"
        >
            {taskLogs?.map((taskLog: TaskLog, index) => (
                <Timeline.Item
                    key={taskLog.id}
                    bullet={
                        index === 0 ? (
                            <IconPlayerPlayFilled size={12} />
                        ) : (
                            <IconArrowRampRight3 size={12} />
                        )
                    }
                    title={
                        taskLog.action.charAt(0).toUpperCase() +
                        taskLog.action.slice(1)
                    }
                >
                    <Text c="dimmed" size="sm">
                        {taskLog.action_performed}
                    </Text>
                    <Text size="xs" mt={4}>
                        {formatDate(taskLog.created_at)}
                    </Text>
                </Timeline.Item>
            ))}
        </Timeline>
    );
}
