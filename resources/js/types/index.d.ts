import { PaginatedTenants, Tenant } from '@/types/tenant';
import { User } from '@/types/user';
import { Config } from 'ziggy-js';

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
        social_auth: boolean;
    };
    tenant: Tenant;
    paginatedTenants: PaginatedTenants;
    ziggy: Config & { location: string };
};
