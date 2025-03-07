import { PaginatedUsers } from '@/types/user';
import { router } from '@inertiajs/react';
import { Group, Pagination } from '@mantine/core';

function TablePagination({ data }: { data: PaginatedUsers }) {
    return (
        <>
            <div className="mt-4 flex justify-end">
                <Pagination.Root
                    total={data?.last_page}
                    value={data?.current_page}
                    getItemProps={(page) => ({
                        href: data?.links[page]?.url,
                        onClick: () => {
                            if (data?.links[page]?.url) {
                                router.visit(data?.links[page].url);
                            }
                        },
                    })}
                >
                    <Group gap={5} justify="center">
                        <Pagination.First
                            onClick={() => router.visit(data?.path)}
                        />
                        <Pagination.Previous
                            onClick={() => router.visit(data?.prev_page_url)}
                        />

                        <Pagination.Items />

                        <Pagination.Next
                            onClick={() => router.visit(data?.next_page_url)}
                        />
                        <Pagination.Last
                            onClick={() => router.visit(data?.last_page_url)}
                        />
                    </Group>
                </Pagination.Root>
            </div>
            <div className="mt-4 flex justify-end">
                <p className="text-sm font-thin text-gray-400">
                    {data?.from} to {data?.to} of {data?.total}
                </p>
            </div>
        </>
    );
}

export default TablePagination;
