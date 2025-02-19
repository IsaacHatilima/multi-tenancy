export interface Tenant {
    id: string;
    tenancy_db_name: string;
    plan: string;
    domain: Domain;
    created_at: string;
    updated_at: string;
}

export interface Domain {
    id: string;
    tenant_id: string;
    domain: string;
    created_at: string;
    updated_at: string;
}
