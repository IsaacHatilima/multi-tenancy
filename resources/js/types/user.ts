export interface User {
    id: number;
    email: string;
    email_verified_at?: string;
    two_factor_secret?: string;
    two_factor_recovery_codes?: string;
    two_factor_confirmed_at?: string;
    copied_codes?: boolean;
    is_active: boolean;
    role: string;
    profile: Profile;
}

export interface Profile {
    id: number;
    first_name: string;
    last_name: string;
    gender: string;
    date_of_birth: string;
}
