import { User } from '@/types/user';

export interface Tenant {
    id: string;
    tenancy_db_name: string;
    name: string;
    address: string;
    city: string;
    state: string;
    country: string;
    zip: string;
    status: string;
    tenant_number: string;
    slug: string;
    contact_first_name: string;
    contact_last_name: string;
    contact_email: string;
    contact_phone: string;
    domain: Domain;
    created_by: User;
    updated_by: User;
    created_at: string;
    updated_at: string;
}

export interface PaginatedTenants {
    current_page: number;
    data: Tenant[];
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
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface Domain {
    id: string;
    tenant_id: string;
    domain: string;
    created_at: string;
    updated_at: string;
}
