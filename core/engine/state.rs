#[derive(Clone, Copy)]
pub enum NetState {
    TrySame,
    TryKnown,
    TryVirtual,
    Notify,
    Done,
}
