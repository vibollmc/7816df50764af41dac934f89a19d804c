using System;

namespace HighLights.Web.Utilities.Model
{
    public class MatchLink
    {
        private string _date;

        public string Link { get; set; }
        public string Name { get; set; }

        public string Date
        {
            get => _date;
            set
            {
                _date = value;

                RDateTime = _date.ToDateTime("MMM d, yyyy");
            }
        }

        public DateTime? RDateTime { get; private set; }
        public string ImageLink { get; set; }

        public string Slug =>
            $"{RDateTime:yyyy-MM-dd}-{Name.Replace(" – ", "-").Replace(" &amp; ", "-").Replace(" & ", "-").Replace(" ", "-")}"
                .ToLower();
    }
}
