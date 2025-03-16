import { PaginationLink } from '@/types/tenant';

export interface User {
    id: number;
    email: string;
    email_verified_at?: string;
    two_factor_secret?: string;
    two_factor_recovery_codes?: string;
    two_factor_confirmed_at?: string;
    two_factor_type?: string;
    two_factor_code?: string;
    two_factor_expires_at?: string;
    copied_codes?: boolean;
    is_active: boolean;
    role: string;
    profile: Profile;
}

export interface TenantUserFilter {
    first_name: string | null;
    last_name: string | null;
    email: string | null;
    role: string | null;
}

export interface Profile {
    id: number;
    first_name: string;
    last_name: string;
    gender: string;
    date_of_birth: string;
}

export type PaginatedUsers = {
    current_page: number;
    data: User[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[];
    next_page_url: string | URL;
    path: string;
    per_page: number;
    prev_page_url: string | URL;
    to: number;
    total: number;
};
