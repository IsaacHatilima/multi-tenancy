import { PaginationLink } from '@/types/tenant';
import { User } from '@/types/user';

export interface Task {
    id: string;
    user_id: User;
    assigned_to: User;
    priority: string;
    escalation: string;
    status: string;
    title: string;
    description: string;
    start: string;
    end: string;
    created_at: string;
    updated_at: string;
}

export interface PaginatedTasks {
    current_page: number;
    data: Task[];
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

export interface TaskLog {
    id: string;
    user_id: User;
    task_id: Task[];
    action: string;
    action_performed: string;
    created_at: string;
}
