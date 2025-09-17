<script setup>
import { Home, User, Settings, LogOut, ChevronUp, User2 } from 'lucide-vue-next'
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from '@/Components/ui/sidebar'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()

// Navigation items
const items = [
  {
    title: "Dashboard",
    url: "dashboard",
    icon: Home,
  },
  {
    title: "Profile",
    url: "profile.edit",
    icon: User,
  },
]

// Get user initials for avatar fallback
const getUserInitials = (name) => {
  return name?.split(' ').map(word => word[0]).join('').toUpperCase() || 'U'
}
</script>

<template>
  <Sidebar collapsible="icon" >
    <SidebarHeader class="border-b border-sidebar-border">
    <div class="flex items-center gap-2 px-2 py-2">
        <!-- Logo kecil (selalu tampil) -->
        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-primary-foreground">
        <span class="text-sm font-bold">IK</span>
        </div>

        <!-- Teks hanya tampil kalau sidebar tidak collapse -->
        <div class="flex flex-col group-data-[collapsible=icon]:hidden">
        <span class="text-sm font-semibold">IK Alumni</span>
        <span class="text-xs text-sidebar-foreground/70">Dashboard</span>
        </div>
    </div>
    </SidebarHeader>


    <SidebarContent>
      <SidebarGroup>
        <SidebarGroupLabel>Navigation</SidebarGroupLabel>
        <SidebarGroupContent>
          <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
              <SidebarMenuButton
                :as-child="true"
                :is-active="route().current(item.url)"
              >
                <Link :href="route(item.url)" class="flex items-center gap-2">
                  <component :is="item.icon" class="h-4 w-4" />
                  <span>{{ item.title }}</span>
                </Link>
              </SidebarMenuButton>
            </SidebarMenuItem>
          </SidebarMenu>
        </SidebarGroupContent>
      </SidebarGroup>
    </SidebarContent>

    <SidebarFooter>
      <SidebarMenu>
        <SidebarMenuItem>
          <DropdownMenu>
            <DropdownMenuTrigger as-child>
              <SidebarMenuButton
                size="lg"
                class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
              >
                <Avatar class="h-8 w-8 rounded-lg">
                  <AvatarImage :src="page.props.auth.user.avatar" :alt="page.props.auth.user.name" />
                  <AvatarFallback class="rounded-lg">
                    {{ getUserInitials(page.props.auth.user.name) }}
                  </AvatarFallback>
                </Avatar>
                <div class="grid flex-1 text-left text-sm leading-tight">
                  <span class="truncate font-semibold">{{ page.props.auth.user.name }}</span>
                  <span class="truncate text-xs">{{ page.props.auth.user.email }}</span>
                </div>
                <ChevronUp class="ml-auto size-4" />
              </SidebarMenuButton>
            </DropdownMenuTrigger>
            <DropdownMenuContent
              class="w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg"
              side="bottom"
              align="end"
              :side-offset="4"
            >
              <DropdownMenuItem as-child>
                <Link :href="route('profile.edit')" class="flex items-center gap-2">
                  <User2 class="h-4 w-4" />
                  <span>Profile</span>
                </Link>
              </DropdownMenuItem>
              <DropdownMenuItem as-child>
                <Link :href="route('logout')" method="post" as="button" class="flex items-center gap-2 w-full">
                  <LogOut class="h-4 w-4" />
                  <span>Log out</span>
                </Link>
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarFooter>
  </Sidebar>
</template>
