using System;

namespace Football.Show.ViewModels
{
    public class Match
    {
        public string Slug { get; set; }
        public string Title { get; set; }
        public DateTime? MatchDate { get; set; }
        public string ImageUrl { get; set; }
    }
}
