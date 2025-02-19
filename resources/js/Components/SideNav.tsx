import { Link } from '@inertiajs/react';
import { Box, NavLink } from '@mantine/core';
import React from 'react';
import { MdSpaceDashboard, MdSupervisorAccount } from 'react-icons/md';

type MenuItem = {
    icon: React.ElementType;
    label: string;
    href: string;
    children?: MenuItem[];
};

export default function SideNav() {
    const data: MenuItem[] = [
        {
            icon: MdSpaceDashboard,
            label: 'Dashboard',
            href: route('dashboard'),
            children: [],
        },
        {
            icon: MdSupervisorAccount,
            label: 'Tenants',
            href: route('tenants'),
            children: [],
        },
    ];

    const items = data.map((item) => (
        <NavLink
            key={item.label}
            href={item.href}
            active={
                window.location.pathname ===
                new URL(item.href, window.location.origin).pathname
            }
            label={item.label}
            leftSection={<item.icon size="1rem" />}
            component={Link}
        >
            {item.children &&
                item.children.length > 0 &&
                item.children.map((child) => (
                    <NavLink
                        key={child.label}
                        href={child.href}
                        active={
                            window.location.pathname ===
                            new URL(child.href, window.location.origin).pathname
                        }
                        label={child.label}
                        className="pl-8"
                        component={Link}
                    />
                ))}
        </NavLink>
    ));

    return (
        <>
            Navbar
            <Box className="mt-2 w-full">{items}</Box>
        </>
    );
}
