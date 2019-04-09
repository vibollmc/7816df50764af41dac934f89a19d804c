namespace Football.Show.Entities
{
    public class TagAssignment
    {
        public int TagId { get; set; }
        public int MatchId { get; set; }

        public virtual Tag Tag { get; set; }
        public virtual Match Match { get; set; }
    }
}
