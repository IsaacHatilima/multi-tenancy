import { MenuItem } from '@/types/menu';
import { Link } from '@inertiajs/react';
import { NavLink } from '@mantine/core';

type SidebarNavProps = {
    items: MenuItem[];
};

function SideNavOptions(props: SidebarNavProps) {
    return (
        <>
            {props.items.map((item) => (
                <NavLink
                    key={item.label}
                    href={item.href}
                    active={
                        window.location.pathname ===
                            new URL(item.href, window.location.origin)
                                .pathname ||
                        window.location.pathname.startsWith(
                            new URL(item.href, window.location.origin)
                                .pathname + '/',
                        )
                    }
                    label={item.label}
                    leftSection={<item.icon size="1rem" />}
                    component={Link}
                >
                    {item.children?.length
                        ? item.children.map((child) => (
                              <NavLink
                                  key={child.label}
                                  href={child.href}
                                  active={
                                      window.location.pathname ===
                                      new URL(
                                          child.href,
                                          window.location.origin,
                                      ).pathname
                                  }
                                  label={child.label}
                                  className="pl-8"
                                  component={Link}
                              />
                          ))
                        : null}
                </NavLink>
            ))}
        </>
    );
}

export default SideNavOptions;
