import { useEffect, useState } from 'react';

const SIZES = ['font-size-md', 'font-size-lg', 'font-size-xl'] as const;
type SizeClass = (typeof SIZES)[number];

export function useFontSize() {
  const [sizeIdx, setSizeIdx] = useState<number>(() => {
    const stored = localStorage.getItem('sp-font-size');
    return stored ? parseInt(stored, 10) : 0;
  });

  useEffect(() => {
    const root = document.documentElement;
    SIZES.forEach((c) => root.classList.remove(c));
    root.classList.add(SIZES[sizeIdx] as SizeClass);
    localStorage.setItem('sp-font-size', String(sizeIdx));
  }, [sizeIdx]);

  const increase = () => setSizeIdx((i) => Math.min(i + 1, SIZES.length - 1));
  const decrease = () => setSizeIdx((i) => Math.max(i - 1, 0));

  return { sizeIdx, increase, decrease, canIncrease: sizeIdx < SIZES.length - 1, canDecrease: sizeIdx > 0 };
}
