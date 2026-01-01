pub fn retry<F>(count: u8, mut f: F) -> bool
where
    F: FnMut() -> bool,
{
    for _ in 0..count {
        if f() {
            return true;
        }
    }
    false
}