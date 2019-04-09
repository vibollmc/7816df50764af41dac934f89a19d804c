using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;

namespace HighLights.Web.Entities
{
    public class Match : Base
    {
        [Required, MaxLength(100)]
        public string Title { get; set; }
        [Required, MaxLength(100)]
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
        [MaxLength(300)]
        public string HomePersonScored { get; set; }
        [MaxLength(300)]
        public string AwayPersonScored { get; set; }
        [MaxLength(300)]
        public string ImageName { get; set; }
        public int? ImageServerId { get; set; }
        public virtual ImageServer ImageServer { get; set; }

        public int? CategoryId { get; set; }
        public virtual Category Category { get; set; }

        public virtual ICollection<Clip> Clips { get; set; }
        public virtual ICollection<Formation> Formations { get; set; }
        public virtual ICollection<TagAssignment> TagAssignments { get; set; }
        public virtual ICollection<Substitution> Substitutions { get; set; }
    }
}
