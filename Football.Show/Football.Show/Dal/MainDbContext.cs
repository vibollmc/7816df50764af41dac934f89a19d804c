using Football.Show.Entities;
using Microsoft.EntityFrameworkCore;

namespace Football.Show.Dal
{
    public class MainDbContext: DbContext
    {
        public virtual DbSet<Category> Categories { get; set; }
        public virtual DbSet<Clip> Clips { get; set; }
        public virtual DbSet<Formation> Formations { get; set; }
        public virtual DbSet<Match> Matchs { get; set; }
        public virtual DbSet<Substitution> Substitutions { get; set; }
        public virtual DbSet<Tag> Tags { get; set; }
        public virtual DbSet<TagAssignment> TagAssignments { get; set; }
        public virtual DbSet<CrawlLink> CrawlLinks { get; set; }
        public virtual DbSet<ImageServer> ImageServers { get; set; }
        public virtual DbSet<CodePlugIn> CodePlugIns { get; set; }

        public MainDbContext(DbContextOptions<MainDbContext> options) : base(options)
        {

        }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            modelBuilder.Entity<Category>()
                .HasIndex(c => c.Slug);

            modelBuilder.Entity<Match>()
                .HasIndex(c => c.Slug);

            modelBuilder.Entity<Tag>()
                .HasIndex(c => c.Slug);

            modelBuilder.Entity<TagAssignment>()
                .HasKey(c => new { c.MatchId, c.TagId });
        }
    }
}
