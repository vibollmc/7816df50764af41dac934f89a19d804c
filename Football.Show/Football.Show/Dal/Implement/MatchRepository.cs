using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Football.Show.Dal.Context;
using Football.Show.Entities;
using Football.Show.Utilities;
using Football.Show.Utilities.Model;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Options;

namespace Football.Show.Dal.Implement
{
    public class MatchRepository : IMatchRepository
    {
        private readonly MainDbContext _dbContext;
        private readonly ViewModels.SiteSetttings _siteSetttings;

        public MatchRepository(LoadDbContext loadDbContext, IOptions<ViewModels.SiteSetttings> siteSetttings)
        {
            _dbContext = loadDbContext.DbContext;
            _siteSetttings = siteSetttings.Value;
        }

        public async Task<bool> CheckExsits(string slug)
        {
            return await _dbContext.Matchs.AnyAsync(x => x.Slug == slug);
        }

        public async Task<bool> Add(Match match, IList<Clip> clips, IList<Formation> formations, IList<Substitution> substitutions, IList<ActionSubstitution> actionSubstitutions)
        {
            if (match == null || clips == null || string.IsNullOrWhiteSpace(match.Competition)) return false;

            using (var dbTransaction = _dbContext.Database.BeginTransaction())
            {
                try
                {
                    //save category
                    var category = await _dbContext.Categories.FirstOrDefaultAsync(x => x.Name.Equals(match.Competition, StringComparison.OrdinalIgnoreCase));

                    if (category == null)
                    {
                        category = new Category
                        {
                            Name = match.Competition,
                            Slug = match.Competition.Replace(" ", "-").ToLower(),
                            IsMenu = false
                        };

                        await _dbContext.Categories.AddAsync(category);
                        await _dbContext.SaveChangesAsync();
                    }

                    //Save match
                    match.CategoryId = category.Id;

                    await _dbContext.Matchs.AddAsync(match);
                    await _dbContext.SaveChangesAsync();

                    //save clip
                    foreach (var clip in clips)
                    {
                        clip.MatchId = match.Id;
                    }

                    await _dbContext.Clips.AddRangeAsync(clips);
                    await _dbContext.SaveChangesAsync();

                    //save formation
                    if (formations != null)
                    {
                        var formationsAdded = formations.Where(x => !x.IsSubstitution).Select(x => new Formation
                        {
                            Name = x.Name,
                            Type = x.Type,
                            Number = x.Number,
                            MatchId = match.Id,
                            IsSubstitution = actionSubstitutions?.Any(y=>y.Out.Equals(x.Name, StringComparison.OrdinalIgnoreCase)) ?? false
                        }).ToList();

                        await _dbContext.Formations.AddRangeAsync(formationsAdded);
                        await _dbContext.SaveChangesAsync();

                        //save substitution
                        if (substitutions != null)
                        {
                            foreach (var substitution in substitutions)
                            {
                                substitution.MatchId = match.Id;
                                var actionSubs = actionSubstitutions?.FirstOrDefault(x =>
                                    x.In.Equals(substitution.Name, StringComparison.OrdinalIgnoreCase));

                                if (actionSubs == null) continue;
                                
                                substitution.Minutes = actionSubs.Min.ToInt();
                                var formation = formationsAdded.FirstOrDefault(x =>
                                    x.Name.Equals(actionSubs.Out, StringComparison.OrdinalIgnoreCase));

                                substitution.FormationId = formation?.Id;
                            }

                            await _dbContext.Substitutions.AddRangeAsync(substitutions);
                            await _dbContext.SaveChangesAsync();
                        }
                    }

                    if (!string.IsNullOrWhiteSpace(match.Home) && !string.IsNullOrWhiteSpace(match.Away))
                    {
                        //Save tag 1
                        var tag1 = await _dbContext.Tags.FirstOrDefaultAsync(x =>
                            x.Name.Equals(match.Home, StringComparison.OrdinalIgnoreCase));
                        var tag2 = await _dbContext.Tags.FirstOrDefaultAsync(x =>
                            x.Name.Equals(match.Away, StringComparison.OrdinalIgnoreCase));
                        if (tag1 == null)
                        {
                            tag1 = new Tag
                            {
                                Name = match.Home,
                                Slug = match.Home.Replace(" ", "-").ToLower()
                            };
                            await _dbContext.Tags.AddAsync(tag1);
                        }
                        if (tag2 == null)
                        {
                            tag2 = new Tag
                            {
                                Name = match.Away,
                                Slug = match.Away.Replace(" ", "-").ToLower()
                            };
                            await _dbContext.Tags.AddAsync(tag2);
                        }

                        await _dbContext.SaveChangesAsync();

                        await _dbContext.TagAssignments.AddAsync(new TagAssignment
                        {
                            TagId = tag1.Id.Value,
                            MatchId = match.Id.Value
                        });

                        await _dbContext.TagAssignments.AddAsync(new TagAssignment
                        {
                            TagId = tag2.Id.Value,
                            MatchId = match.Id.Value
                        });

                        await _dbContext.SaveChangesAsync();
                    }

                    dbTransaction.Commit();
                }
                catch (Exception ex)
                {
                    dbTransaction.Rollback();

                    Console.WriteLine(ex.Message);
                }
            }

            return true;
        }

        public async Task<IEnumerable<ViewModels.Match>> GetMatchs(int page)
        {
            var result = await _dbContext.Matchs
                .Where(x => !x.DeletedAt.HasValue)
                .OrderByDescending(x => x.MatchDate)
                .ThenByDescending(x => x.CreatedAt)
                .Page(page, _siteSetttings.PageSize)
                .Select(x => new ViewModels.Match
                {
                    MatchDate = x.MatchDate,
                    Slug = x.Slug,
                    Title = x.Title,
                    ImageUrl = $"{x.ImageServer.ServerUrl}/{x.ImageServer.Patch}{x.ImageName}"
                }).ToListAsync();

            return result;
        }

        public async Task<int> GetTotalPage()
        {
            var countMatch = await _dbContext.Matchs.CountAsync(x => !x.DeletedAt.HasValue);
            var totalPage = ((double) countMatch / _siteSetttings.PageSize) + 0.49;

            return (int)Math.Round(totalPage, 0, MidpointRounding.ToEven);
        }

        public async Task<ViewModels.MatchDetail> GetMatchDetail(string slug)
        {
            var match = await _dbContext.Matchs
                .Where(x => x.Slug == slug)
                .Select(x => new ViewModels.MatchDetail
                {
                    Home = x.Home,
                    Slug = x.Slug,
                    Away = x.Away,
                    Title = x.Title,
                    Competition = x.Competition,
                    MatchDate = x.MatchDate,
                    AwayManager = x.AwayManager,
                    AwayPersonScored = x.AwayPersonScored,
                    HomeManager = x.HomeManager,
                    HomePersonScored = x.HomePersonScored,
                    Referee = x.Referee,
                    Score = x.Score,
                    Stadium = x.Stadium,
                    Category = x.Category.Name,
                    CategorySlug = x.Category.Slug,
                    ImageUrl = $"{x.ImageServer.ServerUrl}/{x.ImageServer.Patch}{x.ImageName}",
                    Tags = x.TagAssignments.Select(t => new ViewModels.Tag {Slug = t.Tag.Slug, Name = t.Tag.Name}),
                    Clips = x.Clips.Select(c =>
                        new ViewModels.Clip {Name = c.Name, ClipType = c.ClipType, LinkType = c.LinkType, Url = c.Url}),
                    Formations = x.Formations.Select(f => new ViewModels.Formation
                    {
                        Name = f.Name,
                        Number = f.Number,
                        Type = f.Type,
                        IsSubstitution = f.IsSubstitution,
                        SubsName = f.Substitution == null ? null : f.Substitution.Name,
                        SubsNumber = f.Substitution == null ? null : f.Substitution.Number,
                        SubsMinutes = f.Substitution == null ? null : f.Substitution.Minutes
                    }),
                    Substitutions = x.Substitutions.Select(s =>
                        new ViewModels.Substitution {Name = s.Name, Number = s.Number, Type = s.Type})
                })
                .FirstOrDefaultAsync();

            return match;
        }
    }
}
