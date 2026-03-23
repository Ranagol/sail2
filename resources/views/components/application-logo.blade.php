@props(['compact' => false])

<svg
      viewBox="{{ $compact ? '0 0 76 56' : '0 0 220 88' }}"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      aria-label="AWS Cloud"
      {{ $attributes }}
>
      @if ($compact)
            <path
                  d="M16 45h41a8 8 0 0 0 1-15 13 13 0 0 0-25-3 11 11 0 0 0-20 5 7 7 0 0 0 3 13z"
                  fill="currentColor"
                  opacity="0.92"
            />
            <path d="M49 45c4 2 12 2 17 0" stroke="#ff9900" stroke-width="2.6" stroke-linecap="round" />
      @else
            <path
                  d="M47 57h79a16 16 0 0 0 2-31 26 26 0 0 0-49-7 22 22 0 0 0-41 10A14 14 0 0 0 47 57z"
                  fill="currentColor"
                  opacity="0.9"
            />

            <text
                  x="72"
                  y="42"
                  text-anchor="middle"
                  font-family="ui-sans-serif, system-ui, -apple-system, Segoe UI, sans-serif"
                  font-size="14"
                  font-weight="700"
                  fill="white"
            >AWS</text>

            <path d="M146 58c10 6 30 6 42 0" stroke="#ff9900" stroke-width="4" stroke-linecap="round" />
            <path d="M187 54l2 8-8-2" fill="#ff9900" />
      @endif
</svg>
