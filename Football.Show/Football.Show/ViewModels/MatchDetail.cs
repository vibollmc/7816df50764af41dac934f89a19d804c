using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Football.Show.ViewModels
{
    public class MatchDetail
    {
        public string Title { get; set; }
        public string Slug { get; set; }
        public DateTime? MatchDate { get; set; }
        public string Home { get; set; }
        public string Away { get; set; }
        public string HomeManager { get; set; }
        public string AwayManager { get; set; }
        public string Score { get; set; }
        public string Referee { get; set; }
        public string Competition { get; set; }
        public string Stadium { get; set; }
        public string HomePersonScored { get; set; }
        public string AwayPersonScored { get; set; }
        public string ImageUrl { get; set; }
        
        public string Category { get; set; }
        public string CategorySlug { get; set; }

        public IList<Clip> Clips { get; set; }
        public IList<Tag> Tags { get; set; }
        public IList<Substitution> Substitutions { get; set; }
        public IList<Formation> Formations { get; set; }
    }
}
