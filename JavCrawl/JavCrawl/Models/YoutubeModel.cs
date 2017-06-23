using JavCrawl.Models.DbEntity;
using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Models
{
    public class YoutubeModel
    {
        [Display(Name = "Keyword")]
        public string Search { get; set; }
        [Display(Name = "Max Result (0-50)")]
        public int? Max { get; set; }
        [Display(Name = "Latitude")]
        public decimal? Lat { get; set; }
        [Display(Name = "Longitude")]
        public decimal? Lon { get; set; }
        [Display(Name = "Location Radius (ex:  1500m, 5km, 10000ft or 0.75mi)")]
        public string Radius { get; set; }
        [Display(Name = "Published After")]
        public DateTime? PublishedAfter { get; set; }
        [Display(Name = "Comment")]
        public string CommentText { get; set; }
        [Display(Name = "Page Token")]
        public string PageToken { get; set; }

        public IList<YoutubeVideo> Videos { get; set; }

        public IList<string> SelectedVideo { get; set; }
    }
}
