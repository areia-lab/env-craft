<?php

namespace AreiaLab\EnvCraft\Traits;

trait HandlesEnvGrouping
{
    /**
     * Group env keys by prefix (2 or more keys with same prefix)
     */
    private function groupByPrefix(array $env): array
    {
        $groups = [];
        $prefixCount = [];

        // Count how many times each prefix occurs
        foreach ($env as $key => $value) {
            $parts = explode('_', $key);
            $prefix = $parts[0] ?? 'Other';
            $prefixCount[$prefix] = ($prefixCount[$prefix] ?? 0) + 1;
        }

        // Assign keys to groups
        foreach ($env as $key => $value) {
            $parts = explode('_', $key);
            $prefix = $parts[0] ?? 'Other';

            if ($prefixCount[$prefix] >= 2) {
                $groups[$prefix][$key] = $value;
            } else {
                $groups['Other'][$key] = $value;
            }
        }

        return $groups;
    }
}
