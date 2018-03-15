using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Threading.Tasks;

namespace HighLights.Web.Entities
{
    public class Match : Base
    {
        [Required, MaxLength(500)]
        public string Title { get; set; }
        [Required, MaxLength(500)]
        public string Slug { get; set; }
        [Required]
        public DateTime? MatchDate { get; set; }
        [MaxLength(100)]
        public string Home { get; set; }
        [MaxLength(100)]
        public string Away { get; set; }
        [MaxLength(100)]
        public string HomeManager { get; set; }
        [MaxLength(100)]
        public string AwayManager { get; set; }
        [MaxLength(5)]
        public string Score { get; set; }
        [MaxLength(100)]
        public string Referee { get; set; }
        [MaxLength(100)]
        public string Competition { get; set; }
        [MaxLength(100)]
        public string Stadium { get; set; }

        public string ImageName { get; set; }
        public int? ImageServerId { get; set; }
        public ImageServer ImageServer { get; set; }

        public int? CategoryId { get; set; }
        public Category Category { get; set; }

        public ICollection<Clip> Clips { get; set; }
        public ICollection<Formation> Formations { get; set; }
        public ICollection<TagAssignment> TagAssignments { get; set; }
    }
}
