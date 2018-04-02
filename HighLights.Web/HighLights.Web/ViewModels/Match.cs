using System;

namespace HighLights.Web.ViewModels
{
    public class Match
    {
        public string Slug { get; set; }
        public string Title { get; set; }
        public DateTime? MatchDate { get; set; }
        public string ImageUrl { get; set; }
    }
}
