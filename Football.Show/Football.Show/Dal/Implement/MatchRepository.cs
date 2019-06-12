using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Football.Show.Dal.Context;
using Football.Show.Entities;
using Football.Show.Utilities;
using Football.Show.Utilities.Model;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.Options;

namespace Football.Show.Dal.Implement
{
    public class MatchRepository : IMatchRepository
    {
        private readonly MainDbContext _dbContext;
        private readonly ViewModels.SiteSetttings _siteSetttings;
        private readonly ViewModels.TimerSettings _timerSettings;
        private readonly string _domain;

        public MatchRepository(LoadDbContext loadDbContext,
            IConfiguration configuration,
            IOptions<ViewModels.SiteSetttings> siteSetttings, 
            IOptions<ViewModels.TimerSettings> timerSettings)
        {
            _dbContext = loadDbContext.DbContext;
            _siteSetttings = siteSetttings.Value;
            _timerSettings = timerSettings.Value;
            _domain = configuration["DomainHosting"];
        }

        public async Task<bool> CheckExsits(string slug)
        {
            return await _dbContext.Matchs.AnyAsync(x => x.Slug.Equals(slug, StringComparison.OrdinalIgnoreCase) 
                && !(x.CreatedAt.Value > DateTime.UtcNow.AddHours(_timerSettings.UpdateFromHours) && x.CreatedAt.Value <= DateTime.UtcNow));
        }

        private async Task<bool> Update(Match match, IList<Clip> clips, IList<Formation> formations, IList<Substitution> substitutions, IList<ActionSubstitution> actionSubstitutions)
        {
            using(var dbTransaction = await _dbContext.Database.BeginTransactionAsync())
            {
                try
                {
                    var matchDb = await _dbContext.Matchs.FirstOrDefaultAsync(x => x.Slug.Equals(match.Slug));

                    if (matchDb == null) return false;

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
                    matchDb.CategoryId = category.Id;
                    matchDb.Away = match.Away;
                    matchDb.AwayManager = match.AwayManager;
                    matchDb.AwayPersonScored = match.AwayPersonScored;
                    matchDb.Competition = match.Competition;
                    matchDb.Home = match.Home;
                    matchDb.HomeManager = match.HomeManager;
                    matchDb.HomePersonScored = match.HomePersonScored;
                    matchDb.ImageName = match.ImageName;
                    matchDb.ImageServerId = match.ImageServerId;
                    matchDb.MatchDate = match.MatchDate;
                    matchDb.Referee = match.Referee;
                    matchDb.Score = match.Score;
                    matchDb.Stadium = match.Stadium;
                    matchDb.Title = match.Title;
                    matchDb.UpdatedAt = DateTime.UtcNow;

                    await _dbContext.SaveChangesAsync();

                    foreach(var clip in clips)
                    {
                        var clipDb = await _dbContext.Clips.FirstOrDefaultAsync(x => x.ClipType == clip.ClipType && x.MatchId == matchDb.Id);

                        if (clipDb == null)
                        {
                            clip.MatchId = matchDb.Id;
                            await _dbContext.Clips.AddAsync(clip);
                        }
                        else
                        {
                            clipDb.ClipType = clip.ClipType;
                            clipDb.LinkType = clip.LinkType;
                            clipDb.Name = clip.Name;
                            clipDb.Url = clip.Url;
                            clipDb.UpdatedAt = DateTime.UtcNow;
                        }

                        await _dbContext.SaveChangesAsync();
                    }

                    if (await _dbContext.Formations.AnyAsync(x => x.MatchId == matchDb.Id) &&
                        await _dbContext.Substitutions.AnyAsync(x => x.MatchId == matchDb.Id))
                    {
                        dbTransaction.Commit();
                        return true;
                    }

                    //Delete formation
                    var formationDelete = _dbContext.Formations.Where(x => x.MatchId == matchDb.Id);
                    _dbContext.Formations.RemoveRange(formationDelete);
                    var subsDelete = _dbContext.Substitutions.Where(x => x.MatchId == matchDb.Id);
                    _dbContext.Substitutions.RemoveRange(subsDelete);

                    await _dbContext.SaveChangesAsync();

                    //save formation
                    if (formations != null)
                    {
                        var formationsAdded = formations.Where(x => !x.IsSubstitution).Select(x => new Formation
                        {
                            Name = x.Name,
                            Type = x.Type,
                            Number = x.Number,
                            MatchId = matchDb.Id,
                            IsSubstitution = actionSubstitutions?.Any(y => y.Out.Equals(x.Name, StringComparison.OrdinalIgnoreCase)) ?? false,
                            RedCard = x.RedCard,
                            Scores = x.Scores,
                            YellowCard = x.YellowCard
                        }).ToList();

                        await _dbContext.Formations.AddRangeAsync(formationsAdded);
                        await _dbContext.SaveChangesAsync();

                        //save substitution
                        if (substitutions != null)
                        {
                            foreach (var substitution in substitutions)
                            {
                                substitution.MatchId = matchDb.Id;
                                var actionSubs = actionSubstitutions?.FirstOrDefault(x =>
                                    x.In.Equals(substitution.Name, StringComparison.OrdinalIgnoreCase));

                                if (actionSubs == null) continue;

                                substitution.Minutes = actionSubs.Min.ToInt();
                                var formation = formationsAdded.FirstOrDefault(x =>
                                    x.Name.Equals(actionSubs.Out, StringComparison.OrdinalIgnoreCase));

                                substitution.FormationId = formation?.Id;

                                formation = formations
                                    .FirstOrDefault(x => x.IsSubstitution
                                        && x.Type == substitution.Type
                                        && x.Name.Equals(actionSubs.In, StringComparison.OrdinalIgnoreCase));

                                if (formation != null)
                                {
                                    substitution.Scores = formation.Scores;
                                    substitution.RedCard = formation.RedCard;
                                    substitution.YellowCard = formation.YellowCard;
                                }
                            }

                            await _dbContext.Substitutions.AddRangeAsync(substitutions);
                            await _dbContext.SaveChangesAsync();
                        }
                    }

                    dbTransaction.Commit();
                    return true;
                }
                catch (Exception ex)
                {
                    dbTransaction.Rollback();

                    Console.WriteLine(ex.Message);

                    return false;
                }
            }
        }

        public async Task<bool> Add(Match match, IList<Clip> clips, IList<Formation> formations, IList<Substitution> substitutions, IList<ActionSubstitution> actionSubstitutions)
        {
            if (match == null || clips == null || string.IsNullOrWhiteSpace(match.Competition)) return false;

            if (await _dbContext.Matchs.AnyAsync(x => x.Slug.Equals(match.Slug, StringComparison.OrdinalIgnoreCase)))
            {
                return await Update(match, clips, formations, substitutions, actionSubstitutions);
            }

            using (var dbTransaction = await _dbContext.Database.BeginTransactionAsync())
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
                            Slug = match.Competition.Replace(" & ", "-").Replace("&", "-").Replace(" ", "-").ToLower(),
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
                            IsSubstitution = actionSubstitutions?.Any(y=>y.Out.Equals(x.Name, StringComparison.OrdinalIgnoreCase)) ?? false,
                            RedCard = x.RedCard,
                            Scores = x.Scores,
                            YellowCard = x.YellowCard
                        }).ToList();

                        await _dbContext.Formations.AddRangeAsync(formationsAdded);
                        await _dbContext.SaveChangesAsync();

                        //save substitution
                        if (substitutions != null)
                        {
                            foreach (var substitution in substitutions)
                            {
                                substitution.MatchId = match.Id;
                                var actionSubs = actionSubstitutions?.FirstOrDefault(x => x.Type == substitution.Type &&
                                    x.In.Equals(substitution.Name, StringComparison.OrdinalIgnoreCase));

                                if (actionSubs == null) continue;
                                
                                substitution.Minutes = actionSubs.Min.ToInt();
                                var formation = formationsAdded.FirstOrDefault(x => x.Type == substitution.Type &&
                                    x.Name.Equals(actionSubs.Out, StringComparison.OrdinalIgnoreCase));

                                substitution.FormationId = formation?.Id;

                                formation = formations
                                    .FirstOrDefault(x => x.IsSubstitution 
                                        && x.Type == substitution.Type
                                        && x.Name.Equals(actionSubs.In, StringComparison.OrdinalIgnoreCase));

                                if (formation != null)
                                {
                                    substitution.Scores = formation.Scores;
                                    substitution.RedCard = formation.RedCard;
                                    substitution.YellowCard = formation.YellowCard;
                                }
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
                                Slug = match.Home.Replace(" & ", "-").Replace("&", "-").Replace(" ", "-").ToLower()
                            };
                            await _dbContext.Tags.AddAsync(tag1);
                        }
                        if (tag2 == null)
                        {
                            tag2 = new Tag
                            {
                                Name = match.Away,
                                Slug = match.Away.Replace(" & ", "-").Replace("&", "-").Replace(" ", "-").ToLower()
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

        public async Task<ViewModels.PagingResult> GetMatchs(int currentPage)
        {
            var result = new ViewModels.PagingResult();

            var matches = await _dbContext.Matchs
                .Where(x => !x.DeletedAt.HasValue)
                .Include(x => x.ImageServer)
                .OrderByDescending(x => x.MatchDate)
                .ThenByDescending(x => x.CreatedAt)
                .Page(currentPage, _siteSetttings.PageSize)
                .Select(x => x.ToViewModel()).ToListAsync();

            var countMatch = await _dbContext.Matchs.CountAsync(x => !x.DeletedAt.HasValue);
            var totalPage = ((double)countMatch / _siteSetttings.PageSize) + 0.49;

            result.Matches = matches;
            result.TotalPage = (int)Math.Round(totalPage, 0, MidpointRounding.ToEven);
            result.CurrentPage = currentPage;

            return result;
        }

        public async Task<ViewModels.PagingResult> GetMatchsByCategory(int? categoryId, int currentPage)
        {
            var result = new ViewModels.PagingResult();
            
            var matches = await _dbContext.Matchs
                .Where(
                    x => 
                        !x.DeletedAt.HasValue && 
                        x.Category.Id == categoryId &&
                        !x.Category.DeletedAt.HasValue)
                .Include(x => x.ImageServer)
                .OrderByDescending(x => x.MatchDate)
                .ThenByDescending(x => x.CreatedAt)
                .Page(currentPage, _siteSetttings.PageSize)
                .Select(x => x.ToViewModel()).ToListAsync();

            var countMatch = await _dbContext.Matchs
                .CountAsync(x => 
                    !x.DeletedAt.HasValue &&
                    x.Category.Id == categoryId &&
                    !x.Category.DeletedAt.HasValue);

            var totalPage = ((double)countMatch / _siteSetttings.PageSize) + 0.49;

            result.Matches = matches;
            result.TotalPage = (int)Math.Round(totalPage, 0, MidpointRounding.ToEven);
            result.CurrentPage = currentPage;

            return result;
        }

        public async Task<ViewModels.PagingResult> GetMatchsByTag(int currentPage, params int[] tagIds)
        {
            var result = new ViewModels.PagingResult();
            var matches = await _dbContext.Matchs
                .Where(
                    x =>
                        !x.DeletedAt.HasValue &&
                        x.TagAssignments.Any(t => tagIds.Contains(t.TagId)))
                .Include(x => x.ImageServer)
                .OrderByDescending(x => x.MatchDate)
                .ThenByDescending(x => x.CreatedAt)
                .Page(currentPage, _siteSetttings.PageSize)
                .Select(x => x.ToViewModel()).ToListAsync();

            var countMatch = await _dbContext.Matchs
                .CountAsync(x =>
                    !x.DeletedAt.HasValue &&
                    x.TagAssignments.Any(t => tagIds.Contains(t.TagId)));

            var totalPage = ((double)countMatch / _siteSetttings.PageSize) + 0.49;

            result.Matches = matches;
            result.TotalPage = (int)Math.Round(totalPage, 0, MidpointRounding.ToEven);
            result.CurrentPage = currentPage;

            return result;
        }

        public async Task<ViewModels.MatchDetail> GetMatchDetail(string slug)
        {
            var match = await _dbContext.Matchs
                .Where(x => x.Slug.Equals(slug, StringComparison.OrdinalIgnoreCase))
                .Include(x => x.ImageServer)
                .Include(x => x.TagAssignments)
                .Include(x => x.Category)
                .Include(x => x.Clips)
                .Include(x => x.Formations)
                .Include(x => x.Substitutions)
                .Select(x => new ViewModels.MatchDetail
                {
                    Id = x.Id,
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
                    ImageUrl = $"{x.ImageServer.ServerUrl}/{x.ImageName}",
                    Tags = x.TagAssignments.Select(t => new ViewModels.Tag {Slug = t.Tag.Slug, Name = t.Tag.Name}).ToList(),
                    Clips = x.Clips.OrderBy(c => c.ClipType).Select(c =>
                        new ViewModels.Clip {Name = c.Name, ClipType = c.ClipType, LinkType = c.LinkType, Url = c.Url}).ToList(),
                    Formations = x.Formations.OrderBy(f => f.CreatedAt).Select(f => new ViewModels.Formation
                    {
                        Name = f.Name,
                        Number = f.Number,
                        Type = f.Type,
                        YellowCard = f.YellowCard,
                        RedCard = f.RedCard,
                        Scores = f.Scores,
                        IsSubstitution = f.IsSubstitution,
                        SubsName = f.Substitution == null ? null : f.Substitution.Name,
                        SubsNumber = f.Substitution == null ? null : f.Substitution.Number,
                        SubsMinutes = f.Substitution == null ? null : f.Substitution.Minutes,
                        SubsRedCard = f.Substitution == null ? 0 : f.Substitution.RedCard,
                        SubsYellowCard = f.Substitution == null ? 0 : f.Substitution.YellowCard,
                        SubsScores = f.Substitution == null ? 0 : f.Substitution.Scores,

                    }).ToList(),
                    Substitutions = x.Substitutions.OrderBy(s => s.CreatedAt).Select(s =>
                        new ViewModels.Substitution {
                            Name = s.Name,
                            Number = s.Number,
                            Type = s.Type,
                            IsSubstitution = s.FormationId.HasValue,
                            SubsMinutes = s.Minutes,
                            SubsRedCard = s.RedCard,
                            SubsScores = s.Scores,
                            SubsYellowCard = s.YellowCard
                        }).ToList()
                })
                .FirstOrDefaultAsync();

            return match;
        }

        public async Task<IEnumerable<ViewModels.Match>> GetMatchsNewest(int? id)
        {
            return await _dbContext.Matchs
                .Where(x => x.Id != id && !x.DeletedAt.HasValue)
                .OrderByDescending(x => x.MatchDate)
                .ThenByDescending(x => x.CreatedAt)
                .Include(x => x.ImageServer)
                .Take(6)
                .Select(x => x.ToViewModel())
                .ToListAsync();
        }

        public async Task<IList<ViewModels.XmlModel>> GetAllMatchLinks()
        {
            return await _dbContext.Matchs
                .Where(x => !x.DeletedAt.HasValue)
                .Select(x => new ViewModels.XmlModel {
                    Loc = $"http://{_domain}/match/{x.Slug}",
                    LastMod = x.UpdatedAt.HasValue ? x.UpdatedAt.Value : x.CreatedAt.Value,
                    Priority = 0.8,
                    ChangeFreq = "daily"
                })
                .ToListAsync();
        }
    }
}
