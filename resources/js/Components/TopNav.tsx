import { User } from '@/types/user';
import { Link, router } from '@inertiajs/react';
import { ActionIcon, Menu, useMantineColorScheme } from '@mantine/core';
import { CiDark, CiLight, CiUser } from 'react-icons/ci';

export default function TopNav({ user }: { user: User }) {
    const { colorScheme, setColorScheme } = useMantineColorScheme();

    const handleLogout = () => {
        router.post(route('sign.out'));
    };

    return (
        <>
            <div className="pr-4">
                <Menu shadow="md" width={200}>
                    <Menu.Target>
                        <ActionIcon
                            variant="outline"
                            size="md"
                            radius="xl"
                            className="mr-2"
                            onClick={() =>
                                setColorScheme(
                                    colorScheme === 'light' ? 'dark' : 'light',
                                )
                            }
                        >
                            {colorScheme === 'light' ? (
                                <CiDark
                                    style={{ width: '60%', height: '60%' }}
                                />
                            ) : (
                                <CiLight
                                    style={{ width: '60%', height: '60%' }}
                                />
                            )}
                        </ActionIcon>
                    </Menu.Target>
                </Menu>

                <Menu shadow="md" width={200}>
                    <Menu.Target>
                        <ActionIcon variant="outline" size="md" radius="xl">
                            <CiUser style={{ width: '60%', height: '60%' }} />
                        </ActionIcon>
                    </Menu.Target>
                    <Menu.Dropdown>
                        <Menu.Label>
                            {user.profile.first_name} {user.profile.last_name}
                        </Menu.Label>
                        <Menu.Item>
                            <Link
                                href={route('profile.edit')}
                                className="block h-full w-full"
                            >
                                Profile
                            </Link>
                        </Menu.Item>
                        <Menu.Item>
                            <Link
                                href={route('security.edit')}
                                className="block h-full w-full"
                            >
                                Security
                            </Link>
                        </Menu.Item>
                        <Menu.Divider />
                        <Menu.Item color="red" onClick={handleLogout}>
                            Logout
                        </Menu.Item>
                    </Menu.Dropdown>
                </Menu>
            </div>
        </>
    );
}
