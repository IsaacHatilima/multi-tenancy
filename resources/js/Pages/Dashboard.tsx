import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage } from '@inertiajs/react';
import { Badge, Card, Group, Text } from '@mantine/core';
import { useEffect } from 'react';

export default function Dashboard() {
    const tenant = usePage().props.tenant;

    useEffect(() => {
        // console.log(tenant.id);
    }, []);
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <Card shadow="sm" padding="lg" radius="md" withBorder>
                    <Group justify="space-between" mt="md" mb="xs">
                        <Text fw={500}>RILT Starter App {tenant?.id}</Text>
                        <Badge color="pink">V 0.0.1</Badge>
                    </Group>

                    <Text size="sm" c="dimmed">
                        Let's get started'
                    </Text>
                </Card>
            </div>
        </AuthenticatedLayout>
    );
}
