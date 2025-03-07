import React from 'react';

export type MenuItem = {
    icon: React.ElementType;
    label: string;
    href: string;
    children?: MenuItem[];
};
