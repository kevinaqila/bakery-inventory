import { onMounted, ref } from 'vue';

type Appearance = 'light' | 'dark' | 'system';

// Force light mode always - ignore system preference and user selection
export function updateTheme(_value: Appearance) {
    if (typeof window === 'undefined') {
        return;
    }

    document.documentElement.classList.remove('dark');
    document.documentElement.classList.add('light');
}

export function initializeTheme() {
    if (typeof window === 'undefined') {
        return;
    }

    // Force light mode always
    updateTheme('light');
}

const appearance = ref<Appearance>('light');

export function useAppearance() {
    onMounted(() => {
        // Always set to light mode
        appearance.value = 'light';
        updateTheme('light');
    });

    function updateAppearance(_value: Appearance) {
        // Force light mode - ignore user selection
        appearance.value = 'light';
        updateTheme('light');
    }

    return {
        appearance,
        updateAppearance,
    };
}
