import SideNavOptions from '@/Components/SideNavOptions';
import { MenuItem } from '@/types/menu';
import { User } from '@/types/user';
import { Box } from '@mantine/core';
import {
    MdOutlineTaskAlt,
    MdSpaceDashboard,
    MdSupervisorAccount,
} from 'react-icons/md';

function TenantSideNav({ user }: { user: User }) {
    const menuItems: MenuItem[] = [
        {
            icon: MdSpaceDashboard,
            label: 'Dashboard',
            href: route('dashboard'),
            children: [],
        },
        ...(user.role == 'admin'
            ? [
                  {
                      icon: MdSupervisorAccount,
                      label: 'Users',
                      href: route('users'),
                      children: [],
                  },
              ]
            : []),
        {
            icon: MdOutlineTaskAlt,
            label: 'Tasks',
            href: route('tasks.index'),
            children: [],
        },
    ];

    return (
        <>
            Navbar
            <Box className="mt-2 w-full">
                <SideNavOptions items={menuItems} />
            </Box>
        </>
    );
}

export default TenantSideNav;
